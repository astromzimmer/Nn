<?php

namespace Nn\Modules\API;
use Nn\Modules\Node\Node as Node;
use Nn;
use Utils;

class ApiController extends Nn\Core\Controller {
	
	function before() {
		header('Access-Control-Allow-Origin: *');
	}

	function nodes($start=null) {
		Nn::authenticate();
		$nodes = Node::find(array('parent_id'=>0),null,'position');
		$nodes = Utils::exportAll($nodes);
		$json_data = json_encode($nodes);
		$this->renderMode('raw');
		$this->setTemplateVars([
				'data'=>$json_data
			]);
	}
	
	function after() {
	
	}
}

?>