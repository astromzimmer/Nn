<?php

namespace Nn\Controllers;
use Nn\Models\Node as Node;
use Nn\Models\Nodetype as Nodetype;
use Nn\Models\Attributetype as Attributetype;
use Nn;
use Utils;

class NodetypesController extends Nn\Core\Controller {
	
	function before() {
		Nn::authenticate();
	}
	
	function index() {
		$this->setTemplateVars([
				'nodetypes'=> Nodetype::find_all(null,'position')
			]);
	}
	
	function view($id=null) {
		$this->setTemplateVars([
				'nodetypes'=> Nodetype::find_all(null,'position')
			]);
		$this->setTemplateVars([
				'nodetype'=> Nodetype::find($id)
			]);
	}
	
	function sort() {
		$this->renderMode('raw');
		$nodetypes = $_POST['nodetypes'];
		for($i = 0; $i < count($nodetypes); $i++) {
			$nodetype = Nodetype::find($nodetypes[$i]);
			$nodetype->attr('position',$i);
			if(!$nodetype->save()) {
				Utils::sendResponseCode(500);
			}
		}
		Utils::sendResponseCode(200);
	}
	
	function make() {
		$this->setTemplateVars([
				'nodetypes'=> Nodetype::find_all(null,'position')
			]);
		$this->setTemplateVars([
				'attributetypes'=> Attributetype::find_all(null,'position')
			]);
	}
	
	function create() {
		$attributetypes = isset($_POST['attributetypes']) ? $_POST['attributetypes'] : null;
		$nodetype = new Nodetype($_POST['name'],$attributetypes);
		if($nodetype->save()) {
			Utils::redirect_to(DOMAIN.DS.'admin'.DS.'nodetypes');
		} else {
			die("failed to create nodetype");
		}
	}
	
	function edit($id=null) {
		$this->setTemplateVars([
				'nodetypes'=> Nodetype::find_all(null,'position')
			]);
		$this->setTemplateVars([
				'attributetypes'=> Attributetype::find_all(null,'position')
			]);
		$this->setTemplateVars([
				'nodetype'=> Nodetype::find($id)
			]);
	}
	
	function update($id=null) {
		$attributetypes = isset($_POST['attributetypes']) ? $_POST['attributetypes'] : array();
		$nodetype = Nodetype::find($id);
		$nodetype->attr('name',$_POST['name']);
		$nodetype->attr('attributetypes',implode(",",$attributetypes));
		if($nodetype->save()) {
			Utils::redirect_to(DOMAIN.DS.'admin'.DS.'nodetypes');
		} else {
			die("failed to update attribute type");
		}
	}
	
	function delete($id=null) {
		$nodetype = Nodetype::find($id);
		if($nodetype->delete()) {
			Utils::redirect_to(DOMAIN.DS.'admin'.DS.'nodetypes');
		} else {
			die("failed to remove nodetype registration");
		}
	}
}

?>