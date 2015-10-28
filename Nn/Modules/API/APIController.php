<?php

namespace Nn\Modules\Api;
use Nn\Modules\Node\Node as Node;
use Nn;
use Utils;

class ApiController extends Nn\Core\Controller {
	
	function before() {
		header('Access-Control-Allow-Origin: *');
	}

	function nodes($slug=null) {
		// $this->cache('application/json');
		if(!isset($slug)) Utils::sendResponseCode(500);
		$node = Node::find(['slug'=>$slug],1);
		$data = $node->export();
		$json_data = json_encode($data);
		$this->renderMode('json');
		$this->setTemplateVars(['data'=>$json_data]);
	}

	function search() {
		if(isset($_GET['query'])) {
			$query = $_GET['query'];
			$nodes = Node::find(['*slug'=>$query],6);
			$data = Utils::exportAll($nodes);
		} else {
			$data = ['error'=>'No ref in query'];
		}
		$json_data = json_encode($data);
		$this->renderMode('json');
		$this->setTemplateVars([
				'data'=>$json_data
			]);
	}
	
	function after() {
	
	}
}

?>