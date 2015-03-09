<?php

namespace Nn\Controllers;
use Nn\Models\Node as Node;
use Nn\Models\Attributetype as Attributetype;
use Nn\Models\Nodetype as Nodetype;
use Nn;
use Utils;

class NodesController extends Nn\Core\Controller {
	
	function before() {
		Nn::authenticate();
	}
	
	function index() {
		$nodes = Node::find(array('parent_id'=>0),null,'position');
		$this->setTemplateVars([
				'node'=> false
			]);
		$this->setTemplateVars([
				'nodes'=> $nodes
			]);
	}
	
	function view($id=null,$atype_name=null,$edit_attribute_id=null) {
		$node = Node::find($id);
		$nodes = Node::find(array('parent_id'=>0),null,'position');
		$dtype = false;
		$atype = false;
		if(isset($atype_name)) {
			$attributetype = Attributetype::find(array('name'=>$atype_name),1);
			$dtype = $attributetype->attr('datatype');
			$atype = $attributetype;
			$edit_attribute_id = (isset($edit_attribute_id)) ? $edit_attribute_id : false;
		}
		$this->setTemplateVars([
				'node'=> $node,
				'nodes'=> $nodes,
				'dtype'=> $dtype,
				'atype'=> $atype,
				'edit_attribute_id'=> $edit_attribute_id
			]);
	}

	function toggle() {
		$this->renderMode('RAW');
		$visible = $_POST['visible'];
		$node = Node::find($_POST['id']);
		$node->attr('visible',$visible);
		if(!$node->save()) {
			Utils::sendResponseCode(500);
		}
		Utils::sendResponseCode(200);
	}
	
	function sort() {
		$this->renderMode('raw');
		$nodes = $_POST['nodes'];
		$parent_id = $_POST['parent_id'];
		for($i = 0; $i < count($nodes); $i++) {
			$node = Node::find($nodes[$i]);
			$node->attr('position',$i);
			$node->attr('parent_id',$parent_id);
			if(!$node->save()) {
				Utils::sendResponseCode(500);
			}
		}
		Utils::sendResponseCode(200);
	}
	
	function make($in=null,$parent_id=null) {
		$nodes = Node::find(array('parent_id'=>0),null,'position');
		$node = new Node();
		$node->attr('parent_id',$parent_id);
		$parent_id = (isset($parent_id)) ? $parent_id : 0;
		$this->setTemplateVars([
				'node'=> $node,
				'nodes'=> $nodes,
				'nodetypes'=> Nodetype::find_all(null,'position'),
			]);
	}
	
	function edit($id=null) {
		$nodes = Node::find(array('parent_id'=>0),null,'position');
		$this->setTemplateVars([
				'nodes'=> $nodes
			]);
		$this->setTemplateVars([
				'nodetypes'=> Nodetype::find_all(null,'position')
			]);
		if(isset($id) && $id != 0) {
			$node = Node::find($id);
		} else {
			$node = new Node();
		}
		$parents = Node::find(array('-id'=>$node->attr('id')));
		$this->setTemplateVars([
				'node'=> $node
			]);
		$this->setTemplateVars([
				'parents'=> $parents
			]);
	}
	
	function create() {
		$node = new Node($_POST['title'],Nn::session()->user_id(),$_POST['parent_id'],$_POST['nodetype_id']);
		if($node->save()) {
			Utils::redirect_to(DOMAIN.DS.'admin'.DS.'nodes'.DS.'view'.DS.$node->attr('id'));
		} else {
			Utils::redirect_to(DOMAIN.DS.'admin'.DS.'nodes'.DS.'make');
		}
	}
	
	function update($id=null) {
		$node = Node::find($id);
		$parent_id = isset($_POST['parent_id']) ? $_POST['parent_id'] : null;
		$node->fill($_POST['title'],$_POST['slug'],$parent_id,$_POST['nodetype_id']);
		if($node->save()) {
			Utils::redirect_to(DOMAIN.DS.'admin'.DS.'nodes'.DS.'view'.DS.$id);
		} else {
			Utils::redirect_to(DOMAIN.DS.'admin'.DS.'nodes'.DS.'edit'.DS.$id);
		}
	}
	
	function delete($id=null) {
		$node = Node::find($id);
		if($node->recursive_delete()) {
			Utils::redirect_to(DOMAIN.DS.'admin'.DS.'nodes');
		} else {
			die(print_r($node->errors));
		}
	}
}