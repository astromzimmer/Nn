<?php

namespace Nn\Core;
use \Nn;

class Controller extends Basic {

	protected $_template_dir;
	protected $_action;
	protected $_template;
	protected $_cache_id;
	public $render;
	
	function before() {
	
	}
	
	function __construct($action) {
		$this->_action = $action;
		$class_name_array = explode('\\',get_called_class());
		$controller_name = end($class_name_array);
		$this->_template_dir = strtoLower(str_replace('Controller', '', $controller_name));
		$this->render = 1;
		# Here there should be some validation – take AJAX responses, for example
		$this->_template = new Template($this->_template_dir,$this->_action);
		$this->_cache_id = 'RENDER-'.$this->_template_dir.'_'.$this->_action;
	}
	
	function setTemplate($dir=null,$file=null) {
		$this->_template->setFile($dir,$file);
	}

	function setTemplateVars($vars=array()) {
		foreach($vars as $key => $val) {
			$this->_template->set($key, $val);
		}
	}
	
	function renderMode($m,$ct=null) {
		$mode = strtolower($m);
		$content_type = $ct;
		$this->render = 1;
		$this->_template = new Template($this->_template_dir,$this->_action);
		$this->_template->render_as($mode,$content_type);
	}
	
	function cache($actions) {
		if(in_array($this->_action,$actions)) {
			if(Nn::cache()->valid($this->_cache_id)) {
				$this->render = 0;
				die(Nn::cache()->get($cache_id));
			}
		}
	}

	function notFound() {
		echo 'not found';
	}
	
	function __destruct() {
		if($this->render == 1) {
			ob_start();
			$this->_template->render();
			Nn::cache()->set($this->_cache_id,ob_get_contents());
			ob_end_flush();
		}
	}
	
	function after() {
	
	}

}