<?php

namespace Nn\Modules\Attribute;
use Nn\Modules\Attribute\Attribute as Attribute;
use Nn\Modules\Attributetype\Attributetype as Attributetype;
use Nn\Modules\Node\Node as Node;
use Nn;
use Utils;

class AttributesController extends Nn\Core\Controller {

	protected $view;
	
	function before() {
		Nn::authenticate();
	}
	
	function index() {
		$common = Node::find(array('title'=>'Common'),1);
		$logo = $common->attributes('Logo',1);
		$this->setTemplateVars([
				'logo'=>$logo
			]);
		$this->setTemplateVars([
				'attributes'=> Attribute::find()
			]);
	}
	
	function view($id=null) {
		$this->setTemplateVars([
				'attribute'=> Attribute::find($id)
			]);
	}

	function _list($node_id) {
		$node = Node::find($node_id);
		$this->renderMode('partial');
		$this->setTemplateVars([
				'node' => $node
			]);
	}
	
	function sort() {
		$this->renderMode('raw');
		$attributes = $_POST['attributes'];
		$parent_id = $_POST['parent_id'];
		for($i = 0; $i < count($attributes); $i++) {
			$attribute = Attribute::find($attributes[$i]);
			$attribute->attr('position',$i);
			if(!$attribute->save()) {
				Utils::sendResponseCode(500);
			}
		}
		Utils::sendResponseCode(200);
	}

	function toggle() {
		$this->renderMode('RAW');
		$visible = $_POST['visible'];
		$attribute = Attribute::find($_POST['id']);
		$attribute->attr('visible',$visible);
		if(!$attribute->save()) {
			Utils::sendResponseCode(500);
		}
		Utils::sendResponseCode(200);
	}

	function make($atype_id,$in,$node_id) {
		$node = Node::find($node_id);
		$atype = Attributetype::find($atype_id);
		$dtype = $atype->attr('datatype');
		$this->setTemplateVars([
				'node' => $node,
				'atype' => $atype,
				'dtype' => $dtype
			]);
	}
	
	function create() {
		$attribute = Attribute::make($_POST['content']);
		if($attribute->save()) {
			if($attribute->register_as_attribute($_POST['node_id'])) {
				Nn::flash(['success'=>Nn::babel('Attribute created successfully')]);
			} else {
				Nn::flash(['error'=>Nn::babel('Error! Please contact site admin')]);
			}
		} else {
			Nn::flash(['error'=>Nn::babel('Error! Please contact site admin')]);
		}
		Utils::redirect_to(DOMAIN.'/admin/nodes/'.Nn::settings('NODE_VIEW').'/'.$attribute->node()->id);
	}
	
	function edit($id=null) {
		$attribute = Attribute::find($id);
		$node = $attribute->node();
		$this->setTemplateVars([
				'node' => $node,
				'attribute'=> $attribute
			]);
	}
	
	function delete($id=null) {
		$attribute = Attribute::find($id);
		$node_id = $attribute->attr('node_id');
		if($attribute->delete()) {
			Nn::flash(['success'=>Nn::babel('Attribute deleted successfully')]);
		} else {
			Nn::flash(['error'=>Nn::babel('Error! Please contact site admin')]);
		}
		Utils::redirect_to(DOMAIN.'/admin/nodes/'.Nn::settings('NODE_VIEW').'/'.$node_id);
	}
}

?>