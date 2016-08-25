<?php

namespace Nn\Modules\Integer;
use Nn\Modules\Attributetype\Attributetype as Attributetype;
use Nn\Modules\Attribute\Attribute as Attribute;
use Nn;
use Utils;

class IntegersController extends Nn\Core\Controller {
	
	function before() {
		Nn::authenticate();
	}
	
	function create() {
		$atype_id = $_POST['atype_id'];
		$atype = Attributetype::find($atype_id);
		$node_id = $_POST['node_id'];
		$integer = new Integer();
		$number = $_POST['number'];
		if($atype->param('format') == 'timestamp') {
			$number = Utils::strToTime($number);
		}
		$integer->attr('number',$number);
		if($integer->save()) {
			$attribute = new Attribute($node_id,$_POST['atype_id'],$integer->attr('id'));
			if($attribute->save()) {
				Utils::redirect(Nn::s('DOMAIN').'/admin/nodes/'.Nn::settings('NODE_VIEW').'/'.$node_id);
			} else {
				$integer->delete();
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
		$integer = Integer::find($id);
		$number = $_POST['number'];
		if($integer->attributetype()->param('format') == 'timestamp') {
			$number = Utils::strToTime($number);
		}
		$integer->attr('number',$number);
		if($integer->save()) {
			# Attribute save needs error handling - yawn
			$attribute = $integer->attribute();
			$attributetype_id = $_POST['attributetype_id'];
			$attribute->attr('attributetype_id',$attributetype_id);
			$attribute->save();
			Utils::redirect(Nn::s('DOMAIN').'/admin/nodes/'.Nn::settings('NODE_VIEW').'/'.$integer->node()->attr('id'));
		} else {
			die("failed to update number");
		}
	}
	
}

?>