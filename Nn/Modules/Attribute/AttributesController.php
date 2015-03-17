<?php

namespace Nn\Modules\Attribute;
use Nn;
use Utils;

class AttributesController extends Nn\Core\Controller {
	
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
		Utils::redirect_to(DOMAIN.DS.'admin'.DS.'nodes'.DS.'view'.DS.$attribute->node()->id);
	}
	
	function edit($id=null) {
		$attribute = Attribute::find($id);
		Utils::redirect_to(DOMAIN.DS.'admin'.DS.'nodes'.DS.'view'.DS.$attribute->attr('node_id').DS.$attribute->attributetype()->attr('name').DS.$attribute->attr('id'));
	}
	
	function delete($id=null) {
		$attribute = Attribute::find($id);
		$node_id = $attribute->attr('node_id');
		if($attribute->delete()) {
			Nn::flash(['success'=>Nn::babel('Attribute deleted successfully')]);
		} else {
			Nn::flash(['error'=>Nn::babel('Error! Please contact site admin')]);
		}
		Utils::redirect_to(DOMAIN.DS.'admin'.DS.'nodes'.DS.'view'.DS.$node_id);
	}
}

?>