<?php

namespace Nn\Controllers;
use Nn\Models\Feed as Feed;
use Nn\Models\Attribute as Attribute;
use Nn;
use Utils;

class FeedsController extends Nn\Core\Controller {
	
	function before() {
		Nn::authenticate();
	}

	function fetch($id=null) {
		$feed = Feed::find($id);
		if($feed->fetch()) {
			Nn::flash(['success'=>Nn::babel('Feed fetched successfully')]);
		} else {
			Nn::flash(['error'=>Nn::babel('Error! Please contact site admin')]);
		}
		Utils::redirect_to(DOMAIN.'/admin/nodes/view/'.$feed->node()->attr('id'));
	}

	function create() {
		$node_id = $_POST['node_id'];
		$handle = $_POST['handle'];
		$hashtag = $_POST['hashtag'];
		$timeout = $_POST['timeout'];
		$feed = new Feed($handle,$hashtag,$timeout);
		if($feed->save()) {
			$attribute = new Attribute($node_id,$_POST['atype_id'],$feed->attr('id'));
			if($attribute->save()) {
				Nn::flash(['success'=>Nn::babel('Attribute successfully created')]);
			} else {
				$feed->delete();
				Nn::flash(['error'=>Nn::babel('Error! Please contact site admin')]);
				Utils::redirect_to(Nn::referer());
			}
		} else {
			Nn::flash(['error'=>Nn::babel('Error! Please contact site admin')]);
		}
		Utils::redirect_to(DOMAIN.DS.'admin'.DS.'nodes'.DS.'view'.DS.$node_id);
	}
	
	function update($id=null) {
		$node_id = $_POST['node_id'];
		$handle = $_POST['handle'];
		$hashtag = $_POST['hashtag'];
		$timeout = $_POST['timeout'];
		$feed = Feed::find($id);
		$feed->attr('handle',$handle);
		$feed->attr('hashtag',$hashtag);
		$feed->attr('timeout',$timeout);
		if($feed->save()) {
			# Attribute save needs error handling - yawn
			$attribute = $feed->attribute();
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