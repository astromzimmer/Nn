<?php

namespace Nn\Core;

class Basic {

	protected $_rc;
	protected $_errors;
	
	public function __construct() {
		//
	}
	
	protected function getFilePath() {
		return dirname($this->rc()->getFileName());
	}

	protected function getFileExtension() {
		return dirname($this->rc()->getExtension());
	}

	protected function rc() {
		if(!isset($this->rc)) {
			$this->rc = new \ReflectionClass($this);
		}
		return $this->rc;
	}

}