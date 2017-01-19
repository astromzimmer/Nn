<?php

namespace Nn\Modules\Api;
use Nn\Modules\Node\Node as Node;
use Nn;
use Utils;

class ApiController extends Nn\Core\Controller {
	
	function before() {
		$this->renderMode('json');
		header('Access-Control-Allow-Origin: *');
	}

	function info() {
		phpinfo();
		die;
	}

	function search() {
		Nn::settings('HIDE_INVISIBLE',0);
		if(isset($_GET['query'])) {
			$query = $_GET['query'];
			$nodes = Node::find(['*slug'=>$query],6);
			$data = Utils::exportAll($nodes);
		} else {
			$data = ['error'=>'No ref in query'];
		}
		$this->setTemplateVars([
				'data'=>$data
			]);
	}
	
	function after() {
	
	}
}

?>