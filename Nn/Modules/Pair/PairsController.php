<?php

namespace Nn\Modules\Pair;
use Nn\Modules\Attributetype\Attributetype as Attributetype;
use Nn\Modules\Attribute\Attribute as Attribute;
use Nn;
use Utils;

class PairsController extends Nn\Core\Controller {
	
	function before() {
		Nn::authenticate();
	}
	
	function create() {
		$atype_id = $_POST['atype_id'];
		$atype = Attributetype::find($atype_id);
		$node_id = $_POST['node_id'];
		$rkey = $_POST['rkey'];
		$lval = $_POST['lval'];
		$pair = new Pair($rkey,$lval);
		if($pair->save()) {
			$attribute = new Attribute($node_id,$_POST['atype_id'],$pair->attr('id'));
			if($attribute->save()) {
				Utils::redirect(Nn::s('DOMAIN').'/admin/nodes/'.Nn::settings('NODE_VIEW').'/'.$node_id);
			} else {
				$pair->delete();
				Nn::flash(['error'=>Nn::babel("Failed to register attribute")]);
				Utils::redirect(Nn::referer());
			}
		} else {
			Nn::flash(['error'=>Nn::babel("Failed to create number")]);
			Utils::redirect(Nn::referer());
		}
	}
	
	function update($id=null) {
		// $uts = strptime($_POST['number'],DATE_FORMAT);
		// $ts = mktime($uts['tm_hour'],$uts['tm_min'],$uts['tm_sec'],++$uts['tm_mon'],$uts['tm_mday'],($uts['tm_year']+1900));
		$pair = Pair::find($id);
		$rkey = $_POST['rkey'];
		$lval = $_POST['lval'];
		$pair->attr('rkey',$rkey);
		$pair->attr('lval',$lval);
		if($pair->save()) {
			# Attribute save needs error handling - yawn
			$attribute = $pair->attribute();
			$attributetype_id = $_POST['attributetype_id'];
			$attribute->attr('attributetype_id',$attributetype_id);
			$attribute->save();
			Utils::redirect(Nn::s('DOMAIN').'/admin/nodes/'.Nn::settings('NODE_VIEW').'/'.$pair->node()->attr('id'));
		} else {
			die("failed to update number");
		}
	}
	
}

?>