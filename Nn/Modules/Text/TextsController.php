<?php

namespace Nn\Modules\Text;
use Nn\Modules\Attribute\Attribute as Attribute;
use Nn;
use Utils;

class TextsController extends Nn\Core\Controller {
	
	function before() {
		Nn::authenticate();
	}
	
	function create() {
		$node_id = $_POST['node_id'];
		$text = new Text($_POST['content'],$_POST['aMD_markup']);
		if($text->save()) {
			$attribute = new Attribute($node_id,$_POST['atype_id'],$text->attr('id'));
			if($attribute->save()) {
				Nn::flash(['success'=>Nn::babel('Attribute successfully created')]);
			} else {
				$text->delete();
				Nn::flash(['error'=>Nn::babel('Error! Please contact site admin')]);
				Utils::redirect_to(Nn::referer());
			}
		} else {
			Nn::flash(['error'=>Nn::babel('Error! Please contact site admin')]);
		}
		Utils::redirect_to(DOMAIN.DS.'admin'.DS.'nodes'.DS.'view'.DS.$node_id);
	}
	
	function update($id=null) {
		$text = Text::find($id);
		$text = $text->fill($_POST['content'],$_POST['aMD_markup']);
		$node_id = $_POST['node_id'];
		if($text->save()) {
			# Attribute save needs error handling - yawn
			$attribute = $text->attribute();
			$attributetype_id = $_POST['attributetype_id'];
			$attribute->attr('attributetype_id',$attributetype_id);
			$attribute->save();
			Nn::flash(['success'=>Nn::babel('Attribute successfully updated')]);
		} else {
			Nn::flash(['error'=>Nn::babel('Error! Please contact site admin')]);
		}
		Utils::redirect_to(DOMAIN.DS.'admin'.DS.'nodes'.DS.'view'.DS.$node_id);
	}

}

?>