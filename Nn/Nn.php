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
		if($mem_limit) ini_set('memory_limit',self::settings('MEMORY_LIMIT'));
		$upload_max_filesize = self::settings('UPLOAD_MAX_FILESIZE');
		if($upload_max_filesize) ini_set('upload_max_filesize',self::settings('UPLOAD_MAX_FILESIZE'));
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
		} else {
			ini_set('display_startup_errors','Off');
			ini_set('display_errors','Off');
			ini_set('html_errors','Off');
			ini_set('log_errors','On');
			ini_set('error_log',ROOT.DS.'logs'.DS.'production.log');
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

	public static function init(Nn\Core\Session $session,Nn\Core\Minify $minify,Nn\Core\Cache $cache,Nn\Core\Mailer $mailer,Nn\Core\Router $router,Nn\Storage\StorageInterface $storage,Nn\Babel\Dictionary $dictionary,Nn\Trackers\TrackerInterface $tracker) {

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
		self::instance()->router->get($pattern,$callback);
	}

	public static function post($pattern,$callback) {
		self::instance()->router->post($pattern,$callback);
	}

	public static function session() {
		return self::instance()->session;
	}

	public static function storage($name=null,$storage=null) {
		if(isset($name)) {
			$name = 'default';
		} else {
			$name = self::instance()->storage_name;
		}
		if(isset($storage)) {
			return self::instance()->storages[$name] = $storage;
		} else {
			return self::instance()->storages[$name];
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