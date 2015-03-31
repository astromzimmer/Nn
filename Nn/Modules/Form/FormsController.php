<?php

namespace Nn\Modules\Form;
use Nn\Modules\Form\Form as Form;
use Nn\Modules\Attribute\Attribute as Attribute;
use Nn;
use Utils;

class FormsController extends Nn\Core\Controller {

	function before() {
		Nn::authenticate();
	}

	function create() {
		$node_id = $_POST['node_id'];
		$form = new Form($_POST['name'], $_POST['mailto'],$_POST['content'],$_POST['aMD_markup']);
		if($form->save()) {
			$attribute = new Attribute($node_id,$_POST['atype_id'],$form->attr('id'));
			if($attribute->save()) {
				Utils::redirect_to(DOMAIN.DS.'admin'.DS.'nodes'.DS.'view'.DS.$node_id);
			} else {
				$form->delete();
				Nn::flash(['error'=>Nn::babel("Failed to register attribute")]);
				Utils::redirect_to(Nn::referer());
			}
		} else {
			Nn::flash(['error'=>Nn::babel("Failed to create form")]);
			Utils::redirect_to(Nn::referer());
		}
	}
	
	function update($id=null) {
		$form = Form::find($id);
		$form = $form->fill($_POST['name'], $_POST['mailto'], $_POST['content'],$_POST['aMD_markup']);
		$node_id = $_POST['node_id'];
		if($form->save()) {
			# Attribute save needs error handling - yawn
			$attribute = $form->attribute();
			$attributetype_id = $_POST['attributetype_id'];
			$attribute->attr('attributetype_id',$attributetype_id);
			$attribute->save();
			Utils::redirect_to(DOMAIN.DS.'admin'.DS.'nodes'.DS.'view'.DS.$node_id);
		} else {
			die("failed to update form");
		}
	}

}