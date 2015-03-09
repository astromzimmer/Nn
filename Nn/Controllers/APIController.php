<?php

namespace Nn\Controllers;
use Nn\Models\Node as Node;
use Nn;

class ApiController extends Nn\Core\Controller {
	
	function before() {
		Nn::cache(['headlines']);
		header('Access-Control-Allow-Origin: *');
	}

	function artists() {
		$node = Node::find_by_title('artists');
		$node = $node->export();
		$artists = $node['ownNode'];
		$json_data = json_encode($artists);
		$this->renderMode('raw');
		$this->setTemplateVars([
				'raw'=>$json_data
			]);
	}
	
	function after() {
	
	}
}

?>