<?php

namespace Nn\Controllers;
use Nn\Models\Timestamp as Timestamp;
use Nn\Models\Attribute as Attribute;
use Nn;
use Utils;

class TimestampsController extends Nn\Core\Controller {
	
	function before() {
		Nn::authenticate();
	}
	
	function index() {
		$this->setTemplateVars([
				'timestamps'=> Timestamp::find_all()
			]);
	}
	
	function view($id=null) {
		$this->setTemplateVars([
				'timestamps'=> Timestamp::find($id)
			]);
	}
	
	function create() {
		$node_id = $_POST['node_id'];
		$dateTime = new \DateTime();
		$ts = strtotime($_POST['timestamp']);
		$dateTime->setTimestamp($ts);
		$ts += date_offset_get($dateTime);
		$timestamp = new Timestamp();
		$timestamp->attr('timestamp',$ts);
		if($timestamp->save()) {
			$attribute = new Attribute($node_id,$_POST['atype_id'],$timestamp->attr('id'));
			if($attribute->save()) {
				Utils::redirect_to(DOMAIN.DS.'admin'.DS.'nodes'.DS.'view'.DS.$node_id);
			} else {
				$timestamp->delete();
				Nn::flash(['error'=>Nn::babel("Failed to register attribute")]);
				Utils::redirect_to(Nn::referer());
			}
		} else {
			Nn::flash(['error'=>Nn::babel("Failed to create timestamp")]);
			Utils::redirect_to(Nn::referer());
		}
	}
	
	function update($id=null) {
		// $uts = strptime($_POST['timestamp'],DATE_FORMAT);
		// $ts = mktime($uts['tm_hour'],$uts['tm_min'],$uts['tm_sec'],++$uts['tm_mon'],$uts['tm_mday'],($uts['tm_year']+1900));
		$dateTime = new \DateTime();
		$ts = strtotime($_POST['timestamp']);
		$dateTime->setTimestamp($ts);
		$ts += date_offset_get($dateTime);
		$timestamp = Timestamp::find($id);
		$timestamp = $timestamp->fill($ts);
		if($timestamp->save()) {
			# Attribute save needs error handling - yawn
			$attribute = $timestamp->attribute();
			$attributetype_id = $_POST['attributetype_id'];
			$attribute->attr('attributetype_id',$attributetype_id);
			$attribute->save();
			Utils::redirect_to(DOMAIN.DS.'admin'.DS.'nodes'.DS.'view'.DS.$timestamp->node()->attr('id'));
		} else {
			die("failed to update timestamp");
		}
	}
	
}

?>