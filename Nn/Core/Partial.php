<?php

namespace Nn\Core;
use Utils;

class Partial extends Basic {

	protected $vars = array();
	
	function __construct($module="",$path="",$vars=array()) {
		foreach($vars as $key=>$val) {
			$this->set($key, $val);
		}
		$this->render($module,$path);
	}
	
	private function set($name, $value) {
		$this->vars[$name] = $value;
	}
	
	private function render($module,$path) {
		extract($this->vars);
		$template = Utils::fileExists([
				ROOT.DS.'App'.DS.$module.DS.'views'.DS.$path.'.php',
				ROOT.DS.'Nn'.DS.'Modules'.DS.$module.DS.'views'.DS.$path.'.php'
			]);
		if($template) {
			include $template;
			return true;
		}
		return false;
	}

}