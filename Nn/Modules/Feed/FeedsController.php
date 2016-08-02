<?php

namespace Nn\Modules\Feed;
use Nn\Modules\Attribute\Attribute as Attribute;
use Facebook\Facebook as Facebook;
use Nn;
use Utils;

class FeedsController extends Nn\Core\Controller {
	
	function before() {
		
	}

	function authorise($service=null,$feed_id=null) {
		$feed = isset($feed_id) ? Feed::find(['id'=>$feed_id],1,null,false) : false;
		$redirect_uri = DOMAIN."/feeds/authorise/{$service}";
		switch ($service) {
			case 'instagram':
				$client_id = '88527677285c4d148741b17e346109df';
				$client_secret = '56b2b8beecfa40cb9f25038d1665405a';
				if(Utils::is('POST')) {
					Utils::redirect("https://api.instagram.com/oauth/authorize/?client_id={$client_id}&redirect_uri={$redirect_uri}&response_type=code");
					break;
				} else if(isset($_GET['code'])) {
					$params = [
						'client_id' => $client_id,
						'client_secret' => $client_secret,
						'grant_type' => 'authorization_code',
						'redirect_uri' => $redirect_uri,
						'code' => $_GET['code']
					];
					$url = 'https://api.instagram.com/oauth/access_token';
					$json_data = Utils::getURL($url,'POST',$params);
					$result = json_decode($json_data,true);
					if(isset($result['access_token'])) {
						$feed = Feed::find(['handle'=>$result['user']['username']],1,null,false);
						$auth = [
							'user_id' => $result['user']['id'],
							'token' => $result['access_token']
						];
						$feed->attr('auth',json_encode($auth));
						if($feed->save()) {
							Utils::redirect(DOMAIN."/feeds/authorise/done");
						} else {
							Nn::flash(['error'=>Nn::babel('Oups. Could not save.')]);
						}
					} else {
						Nn::flash(['error'=>Nn::babel('Oups. Could not authorise.')]);
					}
				} else {
					if(!$feed)
						Nn::flash(['error'=>Nn::babel('Something wrong with your URL. Please contact whoever gave it to you, and ask them to double-check it.')]);
				}
				break;

			case 'facebook':
				if(!isset($feed_id)) {
					Nn::flash(['error'=>Nn::babel('Something wrong with your URL. Please contact whoever gave it to you, and ask them to double-check it.')]);
					break;
				}
				$redirect_uri = DOMAIN."/feeds/authorise/{$service}/{$feed_id}";
				$client_id = '188474151324215';
				$client_secret = '8f4e8fb0f91b41252ba71390d484a4d9';
				if(Utils::is('POST')) {
					Utils::redirect("https://www.facebook.com/dialog/oauth?client_id={$client_id}&redirect_uri={$redirect_uri}&scope=user_posts");
					break;
				} else if(isset($_GET['code'])) {
					$params = [
						'client_id' => $client_id,
						'client_secret' => $client_secret,
						'redirect_uri' => $redirect_uri,
						'code' => $_GET['code']
					];
					$url = 'https://graph.facebook.com/v2.7/oauth/access_token';
					$json_data = Utils::getURL($url,'GET',$params);
					$result = json_decode($json_data,true);
					if(isset($result['access_token'])) {
						$params = [
							'client_id' => $client_id,
							'client_secret' => $client_secret,
							'grant_type' => 'fb_exchange_token',
							'fb_exchange_token' => $result['access_token']
						];
						$url = 'https://graph.facebook.com/v2.7/oauth/access_token';
						$json_data = Utils::getURL($url,'GET',$params);
						$result = json_decode($json_data,true);
						if(isset($result['access_token'])) {
							$access_token = $result['access_token'];
							$params = [
								'input_token' => $access_token,
								'access_token' => $client_id.'|'.$client_secret
							];
							$url = 'https://graph.facebook.com/debug_token';
							$json_data = Utils::getURL($url,'GET',$params);
							$result = json_decode($json_data,true);
							if(isset($result['data'])) {
								$auth = [
									'user_id' => $result['data']['user_id'],
									'token' => $access_token,
									'expires' => $result['data']['expires_at']
								];
								$feed->attr('auth',json_encode($auth));
								if($feed->save()) {
									Utils::redirect(DOMAIN."/feeds/authorise/done");
								} else {
									Nn::flash(['error'=>Nn::babel('Oups. Could not save.')]);
								}
							} else {
								// handle
							}
						} else {
							// handle
						}
					} else {
						Nn::flash(['error'=>Nn::babel('Oups. Could not authorise.')]);
					}
				} else {
					if(!$feed) {
						Nn::flash(['error'=>Nn::babel('Something wrong with your URL. Please contact whoever gave it to you, and ask them to double-check it.')]);
					}
				}
				break;

			case 'done':
				Nn::flash(['success'=>Nn::babel('Super – thanks!')]);
				break;
			
			default:
				Nn::flash(['error'=>Nn::babel('Something wrong with your URL. Please contact whoever gave it to you, and ask them to double-check it.')]);
				break;
		}
		$this->setTemplateVars([
			'service'=> $service,
			'feed'=> $feed
		]);
	}

	function fetch($id=null) {
		Nn::authenticate();
		$feed = Feed::find($id);
		if($feed->fetch()) {
			Nn::flash(['success'=>Nn::babel('Feed fetched successfully')]);
		} else {
			Nn::flash(['error'=>Nn::babel("Didn't work. Perhaps you'll have to ask the user to authorise ".Nn::settings('PAGE_NAME'))]);
		}
		Utils::redirect(DOMAIN.'/admin/nodes/'.Nn::settings('NODE_VIEW').'/'.$feed->node()->attr('id'));
	}

	function create() {
		Nn::authenticate();
		$node_id = $_POST['node_id'];
		$handle = $_POST['handle'];
		$hashtag = $_POST['hashtag'];
		$since = Utils::strToTime($_POST['since']);
		$until = Utils::strToTime($_POST['until']);
		$feed = new Feed($handle,$hashtag,$since,$until);
		if($feed->save()) {
			$attribute = new Attribute($node_id,$_POST['atype_id'],$feed->attr('id'));
			if($attribute->save()) {
				Nn::flash(['success'=>Nn::babel('Attribute successfully created')]);
			} else {
				$feed->delete();
				Nn::flash(['error'=>Nn::babel('Error! Please contact site admin')]);
				Utils::redirect(Nn::referer());
			}
		} else {
			Nn::flash(['error'=>Nn::babel('Error! Please contact site admin')]);
		}
		Utils::redirect(DOMAIN.'/admin/nodes/'.Nn::settings('NODE_VIEW').'/'.$node_id);
	}
	
	function update($id=null) {
		Nn::authenticate();
		$node_id = $_POST['node_id'];
		$handle = $_POST['handle'];
		$hashtag = $_POST['hashtag'];
		$since = Utils::strToTime($_POST['since']);
		$until = Utils::strToTime($_POST['until']);
		$feed = Feed::find($id);
		$feed->attr('handle',$handle);
		$feed->attr('hashtag',$hashtag);
		$feed->attr('since',$since);
		$feed->attr('until',$until);
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
		Utils::redirect(DOMAIN.'/admin/nodes/'.Nn::settings('NODE_VIEW').'/'.$node_id);
	}

	function toggle() {
		Nn::authenticate();
		$this->renderMode('RAW');
		$visible = $_POST['visible'];
		$post = Post::find($_POST['id']);
		$post->attr('visible',$visible);
		if(!$post->save()) {
			Utils::sendResponseCode(500);
		}
		Utils::sendResponseCode(200);
	}
}

?>