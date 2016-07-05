<?php

use Nn\Modules\Setting\Setting as Setting;
use Nn\Modules\User\User as User;
use Nn\Modules\User\Role as Role;


class Nn extends Nn\Core\Singleton {

	protected $session;
	protected $minify;
	protected $cache;
	protected $mailer;
	protected $router;
	protected $storage_name;
	protected $storages;
	protected $dictionary;

	protected $SETTINGS;

	/*  setup local ini  */
	private static function setIni() {
		$mem_limit = Nn::settings('MEMORY_LIMIT');
		if($mem_limit) ini_set('memory_limit',$mem_limit);
		$post_max_size = self::settings('POST_MAX_SIZE');
		if($post_max_size) ini_set('post_max_size',$post_max_size);
		$upload_max_filesize = self::settings('UPLOAD_MAX_FILESIZE');
		if($upload_max_filesize) ini_set('upload_max_filesize',$upload_max_filesize);
	}

	/* setup environment specific attributes */
	private static function setEnvironmentSpecifics() {
		error_reporting(E_ALL);
		if(DEVELOPMENT_ENV == true) {
			ini_set('display_startup_errors','On');
			ini_set('display_errors','On');
			ini_set('html_errors','On');
			# Disabling dev log for now.
			ini_set('log_errors','Off');
			// ini_set('error_log',ROOT.DS.'logs'.DS.'development.log');
			set_error_handler(NULL);
		} else {
			ini_set('display_startup_errors','Off');
			ini_set('display_errors','On');
			ini_set('html_errors','On');
			ini_set('log_errors','On');
			if(!is_dir(self::settings('LOG_DIR'))) {
				mkdir(self::settings('LOG_DIR'),0755,true);
			}
			ini_set('error_log',self::settings('LOG_DIR').DS.'production.log');
			set_error_handler(['Nn','handleServerErrors']);
		}
	}

	/* strip MQ */
	private static function stripSlash($val) {
		$val = is_array($val) ? array_map(array(self,'stripSlash'), $val) : stripslashes($val);
		return $val;
	}

	private static function stripMQ() {
		ini_set('magic_quotes_gpc','Off');
		ini_set('magic_quotes_runtime','Off');
		ini_set('magic_quotes_sybase','Off');
		# Paranoid double-check
		if(get_magic_quotes_gpc()) {
			$_GET = static::stripSlash($_GET);
			$_POST = static::stripSlash($_POST);
			$_COOKIE = static::stripSlash($_COOKIE);
		}
	}

	public static function handleServerErrors($number,$msg,$file,$line,$vars) {
		$path = $_SERVER['REQUEST_URI'];
		switch ($number) {
			case 8 || 1024:
				$level = 'NOTICE';
				break;
			case 2 || 512:
				$level = 'WARNING';
				break;
			case 1 || 256:
				$level = 'FATAL ERROR';
				break;
			default:
				$level = 'GENERAL';
				break;
		}
		$error_email = self::settings('ERROR_EMAIL');
		if($error_email) {
			$subject = self::settings('PAGE_NAME').': Error';
			$message = "<p><strong>Path:</strong> $path</p>";
			$message .= "<p><strong>Error number:</strong> $number</p>";
			$message .= "<p><strong>Error message:</strong><pre>$msg</pre></p>";
			$message .= "<p><strong>File:</strong> $file</p>";
			$message .= "<p><strong>Line number:</strong> $line</p>";
			$message .= '<p><strong>Vars:</strong><pre>'.print_r($vars,1).'</pre></p>';
			$headers = 'From: '.self::settings('FROM_EMAIL')."\r\n";
			$headers .= 'Content-Type: text/html; charset=utf8'."\r\n";
			mail(self::settings('ERROR_EMAIL'),$subject,$message,$headers);
		}
		$log = date('Y-m-d H:i:s').": $path\r\n";
		$log .= '    LEVEL: '.$level."[".$number."]"."\r\n";
		$log .= '    FILE: '.$file." ($line)\r\n";
		$log .= '    MESSAGE: '.$msg."\r\n";
		$log .= "\r\n".'••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••'."\r\n";
		error_log($log);
		if($level == 'FATAL ERROR') {
			Utils::redirect_to('/500.php');
		}
	}

	public static function init(Nn\Core\Session $session,Nn\Core\Minify $minify,Nn\Core\Cache $cache,Nn\Core\Mailer $mailer,Nn\Core\Router $router,Nn\Storage\StorageInterface $storage,Nn\Babel\Dictionary $dictionary,Nn\Trackers\TrackerInterface $tracker=null) {

		static::setEnvironmentSpecifics();
		static::stripMQ();

		self::instance()->session = $session;
		self::instance()->minify = $minify;
		self::instance()->cache = $cache;
		self::instance()->mailer = $mailer;
		self::instance()->router = $router;
		self::instance()->storage(null,$storage);
		self::instance()->dictionary = $dictionary;
		self::instance()->tracker = $tracker;

		self::instance()->session->init();

		$settings = Setting::find_all();
		foreach($settings as $setting) {
			self::instance()->SETTINGS[strtoupper($setting->attr('name'))] = $setting->attr('value');
		}

		static::setIni();

		# Set initial values
		Nn::settings('HIDE_INVISIBLE',false);

		# Set language
		if($lang = self::instance()->settings('LANGUAGE')) self::instance()->setLanguage($lang);

		# Check for user, and if not, create admin/admin:super
		$admin = User::find();
		if(!$admin) {
			$super_role = new Role('Admins');
			$super_role->save();
			$admin = new User('Jane', 'Doe', 'admin@'.str_replace('http://','',Nn::settings('DOMAIN')), 'password', $super_role->attr('id'));
			$admin->save();
			$editor_role = new Role('Editors');
			$editor_role->save();
			$editor = new User('John', 'Doe', 'editor@'.str_replace('http://','',Nn::settings('DOMAIN')), 'password', $editor_role->attr('id'));
			$editor->save();
		}
	}

	public static function referer() {
		return self::instance()->session->referer();
	}

	public static function flash($fsh=null) {
		return self::instance()->session->flash($fsh);
	}

	public static function run() {

		$route = (isset($_GET['route'])) ? $_GET['route'] : null;
		self::instance()->router->route($route);
	}

	public static function authenticate() {
		if(!self::instance()->session->is_logged_in()) {
			Utils::redirect_to(Nn::settings('DOMAIN').DS.'admin'.DS.'login');
		}
	}

	public static function authenticated($role_name=null) {
		$is_authenticated = self::instance()->session->is_logged_in();
		if($is_authenticated) {
			$user = User::find(self::instance()->session->user_id());
			$role = Role::find($user->attr('role_id'));
			if(isset($role_name)) {
				$is_authenticated = (strtolower($role->attr('name')) == strtolower($role_name));
			} else {
				$is_authenticated = strtolower($role->attr('name'));
			}
		}
		return $is_authenticated;
	}

	public static function setDefaultController($controller) {
		self::instance()->router->setDefaultController($controller);
	}

	public static function setDefaultAction($action) {
		self::instance()->router->setDefaultAction($action);
	}

	public static function getCurrentController() {
		return self::instance()->router->getController();
	}

	public static function getCurrentAction() {
		return self::instance()->router->getAction();
	}

	public static function get($pattern,$callback) {
		self::instance()->router->set($pattern,'get',$callback);
	}

	public static function post($pattern,$callback) {
		self::instance()->router->set($pattern,'post',$callback);
	}

	public static function put($pattern,$callback) {
		self::instance()->router->set($pattern,'put',$callback);
	}

	public static function delete($pattern,$callback) {
		self::instance()->router->set($pattern,'delete',$callback);
	}

	public static function session() {
		return self::instance()->session;
	}

	public static function storage($name=null,$storage=null) {
		if(isset($name) && !is_string($name)) {
			$name = 'default';
		} else {
			$name = self::instance()->storage_name;
		}
		if(isset($storage)) {
			return self::instance()->storages[$name] = $storage;
		} else {
			$storage = self::instance()->storages[$name];
			return isset($storage) ? $storage : false;
		}
	}

	public static function switchStorage($name=null) {
		if(isset($name)) self::instance()->storage_name;
	}

	public static function setLanguage($lang) {
		self::instance()->dictionary->setLanguage($lang);
	}

	public static function babel($phrase) {
		return self::instance()->dictionary->translate($phrase);
	}

	public static function cache() {
		return self::instance()->cache;
	}

	public static function track() {
		self::instance()->tracker->sniff();
	}

	public static function report() {
		return self::instance()->tracker->report();
	}

	public static function settings($key,$value=null) {
		if(isset($value)) return self::instance()->SETTINGS[$key] = $value;
		if(!isset($key)) return false;
		$key = strtoupper($key);
		$soft_setting = isset(self::instance()->SETTINGS[$key]) ? self::instance()->SETTINGS[$key] : false;
		if($soft_setting == 'false') $soft_setting = false;
		$hard_setting = (defined($key)) ? constant($key) : false;
		return ($soft_setting) ? $soft_setting : $hard_setting;
	}

	public static function mailer() {
		return self::instance()->mailer;
	}

	public static function minify() {
		return self::instance()->minify;
	}

	public static function partial($module,$template="",$vars=array()){
		Nn\Core\Template::partial($module,$template,$vars);
	}

}