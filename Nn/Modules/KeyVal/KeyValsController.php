<?php

namespace Nn\Modules\KeyVal;
use Nn\Modules\Attributetype\Attributetype as Attributetype;
use Nn\Modules\Attribute\Attribute as Attribute;
use Nn;
use Utils;

class KeyValsController extends Nn\Core\Controller {
	
	function before() {
		Nn::authenticate();
	}
	
	function create() {
		$atype_id = $_POST['atype_id'];
		$atype = Attributetype::find($atype_id);
		$node_id = $_POST['node_id'];
		$keyVal = new KeyVal();
		$key = $_POST['key'];
		$value = $_POST['value'];
		$keyVal->attr('key',$key);
		$keyVal->attr('value',$value);
		if($keyVal->save()) {
			$attribute = new Attribute($node_id,$_POST['atype_id'],$keyVal->attr('id'));
			if($attribute->save()) {
				Utils::redirect_to(DOMAIN.DS.'admin'.DS.'nodes'.DS.'view'.DS.$node_id);
			} else {
				$keyVal->delete();
				Nn::flash(['error'=>Nn::babel("Failed to register attribute")]);
				Utils::redirect_to(Nn::referer());
			}
		} else {
			Nn::flash(['error'=>Nn::babel("Failed to create number")]);
			Utils::redirect_to(Nn::referer());
		}
	}
	
	function update($id=null) {
		// $uts = strptime($_POST['number'],DATE_FORMAT);
		// $ts = mktime($uts['tm_hour'],$uts['tm_min'],$uts['tm_sec'],++$uts['tm_mon'],$uts['tm_mday'],($uts['tm_year']+1900));
		$keyVal = KeyVal::find($id);
		$key = $_POST['key'];
		$value = $_POST['value'];
		$keyVal->attr('key',$key);
		$keyVal->attr('value',$value);
		if($keyVal->save()) {
			# Attribute save needs error handling - yawn
			$attribute = $keyVal->attribute();
			$attributetype_id = $_POST['attributetype_id'];
			$attribute->attr('attributetype_id',$attributetype_id);
			$attribute->save();
			Utils::redirect_to(DOMAIN.DS.'admin'.DS.'nodes'.DS.'view'.DS.$keyVal->node()->attr('id'));
		} else {
			die("failed to update number");
		}
	}
	
}

?>