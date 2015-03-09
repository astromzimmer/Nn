<?php

namespace Nn\Core;
use Utils;

class Partial extends Basic {

	protected $vars = array();
	
	function __construct($path="", $vars=array()) {
		foreach($vars as $key=>$val) {
			$this->set($key, $val);
		}
		$this->render($path);
	}
	
	private function set($name, $value) {
		$this->vars[$name] = $value;
	}
	
	private function render($path="") {
		extract($this->vars);
		$template = Utils::fileExists([
				ROOT.DS.'App'.DS.'Views'.DS.$path.'.php',
				ROOT.DS.'Nn'.DS.'Views'.DS.$path.'.php'
			]);
		if($template) {
			include $template;
			return true;
		}
		return false;
	}

}