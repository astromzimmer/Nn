<?php

namespace Nn\Core;
use Nn;
use Utils;
use Everzet\Jade\Jade,
	Everzet\Jade\Dumper\PHPDumper,
	Everzet\Jade\Parser,
	Everzet\Jade\Lexer\Lexer,
	Everzet\Jade\Filter\PHPFilter;

class Template extends Basic {

	protected $vars = array();
	protected $_dir;
	protected $_action;
	protected $render_as = "default";
	protected $content_type = 'text/html';
	
	function __construct($module,$action) {
		$this->_dir = $module.DS.'views';
		$this->_action = $action;
	}
	
	public function set($name, $value) {
		$this->vars[$name] = $value;
	}

	public function setFile($dir=null,$file=null) {
		if(isset($dir)) $this->_dir = $dir;
		if(isset($file)) $this->_action = $file;
	}

	public function content_type($ct) {
		$this->content_type = $ct;
	}
	
	public function render_as($mode,$ct=null) {
		if(isset($ct)) {
			$this->content_type = $ct;
		} else {
			switch($mode) {
				case 'default':
				case 'partial':
					$this->content_type = 'text/html';
					break;
				case 'pdf':
					$this->content_type = 'application/pdf';
					break;
				case 'json':
					$this->content_type = 'application/json';
					break;
				case 'raw':
				default:
					$this->content_type = 'text/plain';
			}
		}
		$this->render_as = $mode;
	}

	private function getTemplatePath($extensionless_path) {
		$possibilities = [
				ROOT.DS.'App'.DS.$extensionless_path.'.jade',
				ROOT.DS.'App'.DS.$extensionless_path.'.php',
				ROOT.DS.'Nn'.DS.'Modules'.DS.$extensionless_path.'.jade',
				ROOT.DS.'Nn'.DS.'Modules'.DS.$extensionless_path.'.php'
			];
		return Utils::fileExists($possibilities);
	}

	private static function output($path,$vars=array()) {
		if($path){
			extract($vars);
			$extension = pathinfo($path,PATHINFO_EXTENSION);
			if($extension == 'jade') {
				$dumper = new PHPDumper();
				$dumper->registerFilter('php',new PHPFilter());
				$parser = new Parser(new Lexer());
				$jade = new Jade($parser,$dumper);
				$php_content = $jade->render($path);
				$path = str_replace('.jade', '.php', $path);
				file_put_contents($path, $php_content);
			}
			include $path;
		}
	}

	private function renderTemplate($extensionless_path) {
		if($template = $this->getTemplatePath($extensionless_path)) {
			$this->output($template,$this->vars);
		} else {
			Utils::redirect_to('/404.php');
		}
	}

	private function renderHeader() {
		if($header = $this->getTemplatePath($this->_dir.DS.'header')) {
			$this->output($header,$this->vars);
		} else {
			$this->output($this->getTemplatePath('Def'.DS.'views'.DS.Nn::settings('SESSION_HEADER')),$this->vars);
		}
	}

	private function renderFooter() {
		if($footer = $this->getTemplatePath($this->_dir.DS.'footer')) {
			$this->output($footer,$this->vars);
		} else {
			$this->output($this->getTemplatePath('Def'.DS.'views'.DS.Nn::settings('SESSION_FOOTER')),$this->vars);
		}
	}
	
	public function render() {
		header('Content-Type: '.$this->content_type);
		switch($this->render_as) {
			case 'partial':
			case 'pdf':
				$this->renderTemplate($this->_dir.DS.$this->_action);
				break;
			case 'raw':
			case 'json':
				extract($this->vars);
				if(isset($data)) echo($data);
				break;
			default:
				$this->renderHeader();
				$this->renderTemplate($this->_dir.DS.$this->_action);
				$this->renderFooter();
		}
	}

	public static function partial($module=null,$path=null,$vars=array()) {
		if(is_array($path) || !isset($path)) {
			if(is_array($path)) {
				$vars = $path;
			}
			$path = $module;
			$module = null;
		}
		$template = (!isset($module)) ? $path : Utils::fileExists([
				ROOT.DS.'App'.DS.$module.DS.'views'.DS.$path.'.jade',
				ROOT.DS.'App'.DS.$module.DS.'views'.DS.$path.'.php',
				ROOT.DS.'Nn'.DS.'Modules'.DS.$module.DS.'views'.DS.$path.'.jade',
				ROOT.DS.'Nn'.DS.'Modules'.DS.$module.DS.'views'.DS.$path.'.php'
			]);
		if($template) {
			extract($vars);
			self::output($template,$vars);
		}
	}

}