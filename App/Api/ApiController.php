<?php

namespace App\Api;
use Nn;
use Utils;

class ApiController extends Nn\Core\Controller {
	
	function before() {
		$this->cache([]);
		$this->renderMode('json');
		Nn::settings('HIDE_INVISIBLE',1);
		header('Access-Control-Allow-Origin: *');
	}
	
	function after() {
	
	}
}

?>