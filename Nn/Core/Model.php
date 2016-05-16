<?php

namespace Nn\Core;
use \Nn;
use \Utils;

class Model extends Basic {

	public function attr($var,$val=null) {
		if(isset($val)) {
			$this->$var = $val;
		} else {
			if(!isset($this->$var)) return false;
			return $this->$var;
		}
	}

	public function export($excludes=[]) {
		$export_properties = $this->exportProperties();
		$array = array_diff_key($export_properties, array_flip($excludes));
		array_walk_recursive($array, function(&$val,$key){
			if(method_exists($val, 'exportProperties')) {
				$val = $val->export();
			}
		});
		return $array;
	}

	public function __sleep() {
		return array_keys($this->getAttributes());
	}
	
	function __destruct() {
		
	}

}