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
		$md = isset($_POST['aMD_markup']) ? $_POST['aMD_markup'] : null;
		$text = new Text($_POST['content'],$md);
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
		Utils::redirect_to(DOMAIN.'/admin/nodes/'.Nn::settings('NODE_VIEW').'/'.$node_id);
	}
	
	function update($id=null) {
		$text = Text::find($id);
		$md = isset($_POST['aMD_markup']) ? $_POST['aMD_markup'] : null;
		$text = $text->fill($_POST['content'],$md);
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
		Utils::redirect_to(DOMAIN.'/admin/nodes/'.Nn::settings('NODE_VIEW').'/'.$node_id);
	}

}

?>