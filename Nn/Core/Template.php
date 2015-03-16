<?php

namespace Nn\Core;
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
	protected $jade;
	
	function __construct($module,$action) {
		$this->_dir = $module.DS.'views';
		$this->_action = $action;
	}
	
	function set($name, $value) {
		$this->vars[$name] = $value;
	}

	function setFile($dir=null,$file=null) {
		if(isset($dir)) $this->_dir = $dir;
		if(isset($file)) $this->_action = $file;
	}

	function content_type($ct) {
		$this->content_type = $ct;
	}
	
	function render_as($mode,$ct=null) {
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

	private function output($path) {
		if($path){
			extract($this->vars);
			$extension = pathinfo($path,PATHINFO_EXTENSION);
			if($extension == 'jade') {
				if(!isset($this->jade)) {
					$dumper = new PHPDumper();
					$dumper->registerFilter('php',new PHPFilter());
					$parser = new Parser(new Lexer());
					$this->jade = new Jade($parser,$dumper);
				}
				$php_content = $this->jade->render($path);
				$path = str_replace('.jade', '.php', $path);
				file_put_contents($path, $php_content);
			}
			require_once $path;
		}
	}

	private function renderTemplate($extensionless_path) {
		if($template = $this->getTemplatePath($extensionless_path)) {
			$this->output($template);
		} else {
			Utils::redirect_to('/404.php');
		}
	}

	private function renderHeader() {
		if($header = $this->getTemplatePath($this->_dir.DS.'header')) {
			$this->output($header);
		} else {
			$this->output($this->getTemplatePath('Def'.DS.'views'.DS.SESSION_HEADER));
		}
	}

	private function renderFooter() {
		if($footer = $this->getTemplatePath($this->_dir.DS.'footer')) {
			$this->output($footer);
		} else {
			$this->output($this->getTemplatePath('Def'.DS.'views'.DS.SESSION_FOOTER));
		}
	}
	
	function render() {
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

}