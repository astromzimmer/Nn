<?php

namespace Nn\Core;

abstract class Singleton {

	protected static $instance;

	final protected function __construct() {
		// $this->construct();
	}

	// abstract protected function construct();

	final public static function instance() {
		if(!isset(static::$instance)) {
			$className = get_called_class();
			$new_instance = new $className();
			if(!isset(static::$instance)) {
				static::$instance = $new_instance;
			}
		}
		return static::$instance;
	}

	final public function __clone() {
		trigger_error("Unable to clone singleton class __CLASS__", E_USER_ERROR);
	}

	final public function __wakeup() {
		trigger_error("Unable to unserialise singleton class __CLASS__", E_USER_ERROR);
	}

}

?>