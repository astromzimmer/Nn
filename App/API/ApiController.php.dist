<?php

namespace App\API;
use Nn\Modules\Node\Node as Node;
use Nn\Modules\Feed\Feed as Feed;
use Nn;
use Utils;

class ApiController extends Nn\Core\Controller {
	
	function before() {
		Nn::settings('HIDE_INVISIBLE',1);
		header('Access-Control-Allow-Origin: *');
	}

	function observations() {
		$observations = Node::find_by_type('observation');
		$voice = Utils::exportAll($observations);
		$json_data = json_encode($voice);
		$this->renderMode('json');
		$this->setTemplateVars(['data'=>$json_data]);
	}

	function diary() {
		// $this->cache('application/json');
		$days = Node::find_by_type('day, period');
		$diary = Utils::exportAll($days);
		$json_data = json_encode($diary);
		$this->renderMode('json');
		$this->setTemplateVars(['data'=>$json_data]);
	}

	function artists() {
		// $this->cache('application/json');
		$artists = Node::find_by_type('artist');
		$artists_array = Utils::exportAll($artists);
		foreach($artists as $key=>$artist) {
			$artists_array[$key]['posts'] = Utils::exportAll($this->getPosts($artist,0,9999));
		}
		$json_data = json_encode($artists_array);
		$this->renderMode('json');
		$this->setTemplateVars(['data'=>$json_data]);
	}

	function radio() {
		// $this->cache('application/json');
		$radio = Node::find(['title'=>'Radio']);
		$radio_array = Utils::exportAll($radio);;
		// print_r($radio_array);
		foreach($radio as $key=>$show) {
			$radio_array[$key]['posts'] = Utils::exportAll($this->getPosts($show,0,9999));
		}
		$json_data = json_encode($radio_array);
		$this->renderMode('json');
		$this->setTemplateVars(['data'=>$json_data]);
	}

	function radio_info() {
		$curl = curl_init('http://sdcplayout.airtime.pro/api/live-info/');
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		$json_data = '';
		if($json_data = curl_exec($curl)) {
			# Alles gut!
		} else {
			Utils::sendResponseCode(500);
		}
		$this->renderMode('json');
		$this->setTemplateVars(['data'=>$json_data]);
	}

	function easydb($type) {
		$curl = curl_init('http://eth-travellog.5.easydb.de/api/search?token='.$_GET['token']);
		$json_input = json_encode(['type'=>'object','objecttypes'=>[$type],'generate_rights'=>false]);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $json_input);
		curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
		$json_data = '';
		if($json_data = curl_exec($curl)) {
			# Alles gut!
		} else {
			Utils::sendResponseCode(500);
		}
		$this->renderMode('json');
		$this->setTemplateVars(['data'=>$json_data]);
	}

	private function getPosts($artist,$offs=null,$amt=null) {
		$offset = isset($offs) ? $offs : 0;
		$amount = isset($amt) ? $amt : 12;
		if($artist) {
			$posts = $artist->children_by_type('interview,booklet');
			if(!is_array($posts)) $posts = [];
			$services = $artist->attributes(['Facebook','Wordpress','SoundCloud','Twitter','Tumblr','Vimeo','YouTube']);
			if(!is_array($services)) $services = [];
			foreach($services as $service) {
				$service_array = $service->data()->posts();
				if(!empty($service_array)) $posts = array_merge($posts,$service_array);
			}
		} else {
			// $posts = Node::find_by_type('Post');
			// if(!is_array($posts)) $posts = [];
			// $artists = Node::find_by_type('artist');
			// if($artists) {
			// 	foreach($artists as $artst) {
			// 		$services = $artst->attributes(['Facebook','SoundCloud','Twitter','Tumblr','Vimeo','YouTube']);
			// 		if(!is_array($services)) $services = [];
			// 		foreach($services as $service) {
			// 			$service_array = $service->data()->posts();
			// 			if(!empty($service_array)) $posts = array_merge($posts,$service->data()->posts());
			// 		}
			// 	}
			// }
		}
		$posts = Utils::sortByDate($posts);
		$posts = array_slice($posts,$offset,$amount);
		return $posts;
	}

	function cron() {
		$feeds = Feed::find_all();
		$results = [];
		foreach($feeds as $feed){
			if($feed->fetch()) {
				array_push($results,'Feed for handle "'.$feed->attr('handle').'" fetched successfully.');
			} else {
				array_push($results,'Error fetching feed for handle "'.$feed->attr('handle').'".');
			}
		}
		Nn::cache()->flush('Feeds');
		$this->renderMode('json');
		$json_data = json_encode($results);
		$this->setTemplateVars(['data'=>$json_data]);
	}
	
	function after() {
	
	}
}

?>