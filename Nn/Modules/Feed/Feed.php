<?php

namespace Nn\Modules\Feed;
use Nn\Modules\Feed\Post as Post;
use Nn;
use Utils;
use Facebook\Facebook as Facebook;
use Abraham\TwitterOAuth\TwitterOAuth as TwitterOAuth;
use Vimeo\Vimeo as Vimeo;

class Feed extends Nn\Modules\Datatype\Datatype {
	
	protected $handle;
	protected $auth;
	protected $hashtag;
	protected $since;
	protected $until;

	public static $SCHEMA = array(
			'handle' => 'short_text',
			'auth' => 'text',
			'hashtag' => 'short_text',
			'since' => 'integer',
			'until' => 'integer',
			'created_at' => 'double',
			'updated_at' => 'double'
		);

	public static $PARAMS = array(
			'service' => [
					'Facebook',
					'Instagram',
					'Twitter',
					'Tumblr',
					'Vimeo',
					'YouTube',
					'SoundCloud',
					'flickr',
					'Wordpress',
					'JSON'
				]
		);

	public function exportProperties() {
		return array(
			'handle'		=>	$this->handle,
			'hashtag'		=>	$this->hashtag,
			'since'			=>	$this->since,
			'until'			=>	$this->until,
			'created_at'	=>	$this->created_at,
			'updated_at'	=>	$this->updated_at,
		);
	}
	
	public function __construct($handle=null,$hashtag=null,$since=null,$until=null){
		if(!empty($handle)){
			$this->handle = $handle;
			$this->hashtag = $hashtag;
			$this->since = $since;
			$this->until = $until;
			return $this;
		} else {
			return false;
		}
	}

	public function service() {
		return $this->attributetype()->params()['service'];
	}

	public function since() {
		return strftime(Nn::settings('DATE_FORMAT'),$this->since);
	}

	public function until() {
		return strftime(Nn::settings('DATE_FORMAT'),$this->until);
	}

	public function fetch() {
		$attribute = $this->attribute();
		if(!$attribute) return false;
		$attributetype = $attribute->attributetype();
		$params = json_decode($attributetype->attr('params'),true);
		$service = strtolower($params['service']);
		$result = $this->$service();
		if($result) {
			Nn::cache()->flush('api_getPosts_');
			return $this->save();
		}
		return false;
	}

	public function posts() {
		return Post::find(['feed_id'=>$this->id]);
	}

	private function auth() {
		return json_decode($this->auth,true);
	}

	private function facebook() {
		$appId = '188474151324215';
		$secret = '8f4e8fb0f91b41252ba71390d484a4d9';
		$fb = new Facebook([
				'app_id' => $appId,
				'app_secret' => $secret,
				'default_graph_version' => 'v2.7'
			]);
		$params = '?limit=100&fields=type,source,place,picture,object_id,message,link,caption,created_time,attachments';
		if($this->since) $params .= '&since='.$this->since;
		if($this->until) $params .= '&until='.$this->until;
		// CHECK IF PAGE OF USER
		$auth = $this->auth();
		if($auth == 'AUTH') {
			Nn::cache()->flush('feeds');
			return false;
		}
		if(isset($auth['user_id']) && isset($auth['token'])) {
			$handle = $auth['user_id'];
			$fb->setDefaultAccessToken($auth['token']);
		} else {
			$handle = $this->handle;
			$fb->setDefaultAccessToken($appId.'|'.$secret);
		}
		try {
			$response = $fb->get('/'.$handle.'/posts'.$params);
		} catch(Exception $e) {
			$this->attr('auth','AUTH');
			$this->save();
			return false;
		}
		$body = $response->getBody();
		$edge = $response->getGraphEdge();
		if(isset($edge)) {
			// $items = array();
			$this->parseFacebookResults($fb,$edge);
			return true;
		} else {
			return false;
		}
	}

	private function parseFacebookResults($fb,$edge) {
		foreach ($edge as $graphNode) {
			$json_obj = $graphNode->asJson();
			if(strpos($json_obj,'#'.$this->hashtag) !== false) {
				$obj = $graphNode->asArray();
				if(isset($obj['message']) || isset($obj['name'])) {
					switch($obj['type']) {
						case 'photo':
						case 'video':
							// try {
							// 	$photo_response = $fb->get('/'.$obj['id'].'?fields=images');
							// 	$photo = $photo_response->getGraphObject()->asArray();
							// 	$obj['picture'] = $photo['images'][0]['source'];
							// } catch(\Exception $e) {
							// 	try {
							// 		$photo_response = $fb->get('/'.$obj['id'].'?fields=full_picture');
							// 		$photo = $photo_response->getGraphObject()->asArray();
							// 		$obj['picture'] = $photo['full_picture'];
							// 	} catch(\Exception $e) {
							// 		# Fucked up
							// 		if(isset($obj['full_picture'])) {
							// 			$obj['picture'] = $obj['full_picture'];
							// 		}
							// 	}
							// }
							if(isset($obj['attachments'])) {
								if(isset($obj['attachments'][0]['media'])) {
									$obj['picture'] = $obj['attachments'][0]['media']['image']['src'];
								} else if(isset($obj['attachments'][0]['subattachments'])) {
									$obj['picture'] = $obj['attachments'][0]['subattachments'][0]['media']['image']['src'];
								}
							}
							break;
						case 'link':
						case 'event':
							try {
								$link_response = $fb->get('/'.$obj['id'].'?fields=full_picture');
								$link = $link_response->getGraphObject()->asArray();
								if(isset($link['full_picture'])) $obj['picture'] = $link['full_picture'];
							} catch(\Exception $e) {
								# Error handling
							}
							break;
					}
					$uid = $obj['id'];
					$post = Post::find(['uid'=>$uid,'feed_id'=>$this->id],1,null,false);
					$created_at = is_object($obj['created_time']) ? $obj['created_time']->getTimestamp() : strtotime($obj['created_time']);
					if(!$post){
						$post = new Post();
						$post->attr('uid',$uid);
						$post->attr('visible',true);
						$post->attr('feed_id',$this->id);
						$post->attr('created_at',$created_at);
					}
					$json_obj = json_encode($obj);
					if($post->attr('content') != $json_obj) {
						$post->attr('content',$json_obj);
						$post->attr('created_at',$created_at);
						$post->save();
					}
				}
			}
		}
		$next = $fb->next($edge);
		if($next) $this->parseFacebookResults($fb,$next);
	}

	private function instagram() {
		$client_id = '88527677285c4d148741b17e346109df';
		$client_secret = '56b2b8beecfa40cb9f25038d1665405a';
		$auth = $this->auth();
		if(isset($auth['token'])) {
			$user_id = $auth['user_id'];
			$token = $auth['token'];
			$url = "https://api.instagram.com/v1/users/{$user_id}/media/recent?access_token={$token}";
			$raw_result = Utils::getURL($url);
			$result = json_decode($raw_result,true);
			if(isset($result['data'])) {
				$medias = $result['data'];
				foreach($medias as $media) {
					$json_media = json_encode($media);
					if(strpos($json_media,'#'.$this->hashtag) !== false) {
						$uid = $media['id'];
						$post = Post::find(['uid'=>$uid,'feed_id'=>$this->id],1,null,false);
						if(!$post){
							$post = new Post();
							$post->attr('uid',$uid);
							$post->attr('visible',true);
							$post->attr('feed_id',$this->id);
							$post->attr('created_at',$media['created_time']);
						}
						$post->attr('content',$json_media);
						$post->save();
					}
				}
				return true;
			}
		} else {
			$this->attr('auth','AUTH');
			$this->save();
		}
		return false;
	}

	private function twitter() {
		$consumer_key = 'mm6dM0e47IinHYOViQ9pg';
		$consumer_secret = 'GKpeQglxnnUk9zNSaXd56gG2ir22WRB2NDW9ntFGSY';
		$access_token = '377840296-VicUNH5cWYHkjtPgZTzQhYothTI4QygzrBdHfbMO';
		$access_token_secret = 'Os7zGput5SsLRDAX5jMBVQewT9WnsAgmlzcgycsYK9E';
		$params = [
				'q' => "from:{$this->handle} #{$this->hashtag}"
				// 'q' => "from:naypinya"
			];
		$twitter = new TwitterOAuth($consumer_key,$consumer_secret,$access_token,$access_token_secret);
		$result = $twitter->get('search/tweets',$params);
		if(isset($result->statuses)) {
			foreach ($result->statuses as $value) {
				$uid = $value->id;
				$post = Post::find(['uid'=>$uid,'feed_id'=>$this->id],1,null,false);
				if(!$post){
					$post = new Post();
					$post->attr('uid',$uid);
					$post->attr('visible',true);
					$post->attr('feed_id',$this->id);
					$post->attr('created_at',strtotime($value->created_at));
				}
				if(isset($value->user)) unset($value->user);
				$json_obj = json_encode($value);
				if($post->attr('content') != $json_obj) {
					$post->attr('content',$json_obj);
					$post->save();
				}
			}
			return true;
		} else {
			return false;
		}
	}

	private function vimeo() {
		$api_key = '67103ea122ffdf4b60d4d451ccbd13738e126e9a';
		$api_secret = 'f7f0fca1058b53c4d5e36561a945d7282e327f38';
		$vimeo = new Vimeo($api_key,$api_secret);
		$token = $vimeo->clientCredentials();
		$vimeo->setToken($token['body']['access_token']);
		$result = $vimeo->request("/users/{$this->handle}/videos",[],'GET');
		if($result['status'] == 200) {
			$data = $result['body']['data'];
			$items = [];
			foreach ($data as $video) {
				if(count($video['tags']) > 0){
					$tagged = false;
					if(empty($this->hashtag)) {
						$tagged = true;
					} else {
						foreach($video['tags'] as $tag) {
							if($tag['tag'] == $this->hashtag){
								$tagged = true;
								break;
							}
						}
					}
					if($tagged) {
						$uri_array = explode('/',$video['uri']);
						$uid = array_pop($uri_array);
						$post = Post::find(['uid'=>$uid,'feed_id'=>$this->id],1,null,false);
						if(!$post){
							$post = new Post();
							$post->attr('uid',$uid);
							$post->attr('visible',true);
							$post->attr('feed_id',$this->id);
							$post->attr('created_at',strtotime($video['created_time']));
						}
						$post->attr('content',json_encode($video));
						$post->save();
					}
				}
			}
			return true;
		} else {
			return false;
		}
	}

	private function youtube() {
		// $google = new \Google_Client();
		// $google->setDeveloperKey('AIzaSyCTRZXXDHR7lXIZYww_NCZYjd6WNemJ3SA');
		// $youtube = new \Google_YouTubeService($google);
		// $result = $youtube->playlistItems->listPlaylistItems('snippet',array('playlistId'=>$this->handle));
		$key = 'AIzaSyCTRZXXDHR7lXIZYww_NCZYjd6WNemJ3SA';
		// $channels_url = "https://www.googleapis.com/youtube/v3/channels?part=contentDetails&forUsername={$this->handle}&key={$key}";
		$channels_url = "https://www.googleapis.com/youtube/v3/channels?part=contentDetails&id={$this->handle}&key={$key}";
		$channels = json_decode(Utils::getURL($channels_url),true);
		if(!isset($channels['items']) || count($channels['items']) == 0) return false;
		$uploads_id = $channels['items'][0]['contentDetails']['relatedPlaylists']['uploads'];
		$url = "https://www.googleapis.com/youtube/v3/playlistItems?part=snippet&playlistId={$uploads_id}&key={$key}";
		$result = json_decode(Utils::getURL($url),true);
		if($items = $result['items']) {
			foreach ($items as $key => $value) {
				$json_item = json_encode($value);
				if(strpos(strtolower($json_item),'#'.$this->hashtag) !== false) {
					$uid = $value['id'];
					$post = Post::find(['uid'=>$uid,'feed_id'=>$this->id],1,null,false);
					if(!$post){
						$post = new Post();
						$post->attr('uid',$uid);
						$post->attr('visible',true);
						$post->attr('feed_id',$this->id);
						$post->attr('created_at',strtotime($value['snippet']['publishedAt']));
					}
					$post->attr('content',$json_item);
					$post->save();
				}
			}
			return true;
		} else {
			return false;
		}
	}

	private function soundcloud() {
		$api_key = 'b59aa0f9cb299f101a5ab2a738ac7f1b';
		//$api_secret = '9ce6edc92e5f8777e86eb6339c51cbf7';
		$url = "http://api.soundcloud.com/users/{$this->handle}/tracks.json?client_id={$api_key}";
		$result = json_decode(Utils::getURL($url),true);
		if(!isset($result['errors']) && is_array($result)){
			foreach ($result as $key => $value) {
				// if(isset($value['tag_list'])){
					// if(empty($this->hashtag) || strpos(strtolower($value['tag_list']),$this->hashtag) !== false){
					$json_item = json_encode($value);
					if(strpos(strtolower($json_item),$this->hashtag) !== false) {
						$uid = $value['id'];
						$post = Post::find(['uid'=>$uid,'feed_id'=>$this->id],1,null,false);
						if(!$post){
							$post = new Post();
							$post->attr('uid',$uid);
							$post->attr('visible',true);
							$post->attr('feed_id',$this->id);
							$post->attr('created_at',strtotime($value['created_at']));
						}
						$post->attr('content',$json_item);
						$post->save();
					}
				// }
			}
			return true;
		} else {
			return false;
		}
	}

	private function flickr() {
		$api_key = 'a315c13f9dd88cf52d1523871dc6ac2e';
		//$api_secret = '69e255ce19ff4a0e';
		$url = "http://ycpi.api.flickr.com/services/rest/?method=flickr.photos.search&format=json&api_key={$api_key}&user_id={$this->handle}&extras=url_n,url_z,date_taken,date_upload&tags=sdc2013";
		// $url = "http://ycpi.api.flickr.com/services/rest/?method=flickr.photos.search&format=json&api_key={$api_key}&user_id={$this->handle}&extras=description,url_n,url_z,date_taken,date_upload";
		$result = Utils::getURL($url);
		$result = str_replace('jsonFlickrApi(','',$result);
		$result = substr($result,0,strlen($result)-1);
		$result = json_decode($result,true);
		if(isset($result['photos'])) {
			$items = $result['photos']['photo'];
			foreach ($items as $key => $value) {
				$uid = $value['id'];
				$post = Post::find(['uid'=>$uid,'feed_id'=>$this->id],1,null,false);
				if(!$post){
					$post = new Post();
					$post->attr('uid',$uid);
					$post->attr('visible',true);
					$post->attr('feed_id',$this->id);
					$post->attr('created_at',strtotime($value['dateupload']));
				}
				$post->attr('content',json_encode($value));
				$post->save();
			}
			return true;
		} else {
			return false;
		}
	}

	private function tumblr() {
		$key = 'TvdxUNY4jYGBgFVcdAtIUZjtpucmJG4l8IBTE7Kx7yYv3UsYgC';
		$url = "http://api.tumblr.com/v2/blog/{$this->handle}/posts?tag={$this->hashtag}&api_key={$key}";
		$result = json_decode(Utils::getURL($url),true);
		if($response = $result['response']) {
			$items = $response['posts'];
			foreach ($items as $key => $value) {
				$uid = $value['id'];
				$post = Post::find(['uid'=>$uid,'feed_id'=>$this->id],1,null,false);
				if(!$post){
					$post = new Post();
					$post->attr('uid',$uid);
					$post->attr('visible',true);
					$post->attr('feed_id',$this->id);
					$post->attr('created_at',$value['timestamp']);
				}
				$post->attr('content',json_encode($value));
				$post->save();
			}
			return true;
		} else {
			return false;
		}
	}

	private function wordpress() {
		$url_handle = rawurlencode($this->handle);
		$url = "https://public-api.wordpress.com/rest/v1/sites/{$url_handle}/posts/?number=100&pretty=0&tag={$this->hashtag}";
		$result = json_decode(Utils::getURL($url),true);
		if(isset($result['posts']) && $items = $result['posts']) {
			foreach ($items as $key => $value) {
				$uid = $value['ID'];
				$post = Post::find(['uid'=>$uid,'feed_id'=>$this->id],1,null,false);
				if(!$post){
					$post = new Post();
					$post->attr('uid',$uid);
					$post->attr('visible',true);
					$post->attr('feed_id',$this->id);
					$post->attr('created_at',strtotime($value['date']));
				}
				$post->attr('content',json_encode($value));
				$post->save();
			}
			return true;
		} else {
			return false;
		}
	}

	private function json() {
		$url = $this->handle;
		$result = json_decode(Utils::getURL($url),true);
		if(isset($result)) {
			$items = $result;
			foreach ($items as $key => $value) {
				$uid = $value['id'];
				$post = Post::find(['uid'=>$uid,'feed_id'=>$this->id],1,null,false);
				if(!$post){
					$post = new Post();
					$post->attr('uid',$uid);
					$post->attr('visible',true);
					$post->attr('feed_id',$this->id);
					$post->attr('created_at',strtotime($value['created_at']));
				}
				$post->attr('content',json_encode($value));
				$post->save();
			}
			return true;
		} else {
			return false;
		}
	}

}

?>