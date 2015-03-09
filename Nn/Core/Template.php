<?php

namespace Nn\Core;
use Utils;

class Template extends Basic {

	protected $vars = array();
	protected $_dir;
	protected $_action;
	protected $render_as = "default";
	protected $content_type = 'text/html';
	
	function __construct($dir,$action) {
		$this->_dir = $dir;
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

	function getTemplatePath($extensionless_path) {

		return Utils::fileExists([
				ROOT.DS.'App'.DS.'Views'.DS.$extensionless_path.'.php',
				ROOT.DS.'Nn'.DS.'Views'.DS.$extensionless_path.'.php',
			]);
	}

	private function renderHeader() {
		if($header = $this->getTemplatePath($this->_dir.DS.'header')) {
			return $header;
		} else {
			return $this->getTemplatePath('default'.DS.SESSION_HEADER);
		}
	}

	private function renderTemplate($extensionless_path) {
		if($template = $this->getTemplatePath($extensionless_path)) {
			return $template;
		} else {
			// Utils::redirect_to('/404.php');
		}
	}

	private function renderFooter() {
		if($footer = $this->getTemplatePath($this->_dir.DS.'footer')) {
			return $footer;
		} else {
			return $this->getTemplatePath('default'.DS.SESSION_FOOTER);
		}
	}
	
	function render() {
		extract($this->vars);
		header('Content-Type: '.$this->content_type);
		if($this->render_as == "default") {
			include_once $this->renderHeader();
			include_once $this->renderTemplate($this->_dir.DS.$this->_action);
			include_once $this->renderFooter();
		} elseif($this->render_as == "partial") {
			include_once $this->renderTemplate($this->_dir.DS.$this->_action);
		} elseif($this->render_as == 'pdf') {
			include_once $this->renderTemplate($this->_dir.DS.$this->_action);
		} elseif($this->render_as == 'raw' || $this->render_as == 'json') {
			if(isset($data)) echo($data);
		}
	}

}