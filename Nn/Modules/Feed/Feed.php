<?php

namespace Nn\Modules\Feed;
use Nn\Modules\Feed\Post as Post;
use Nn;
use Utils;
use Facebook\Facebook as Facebook;
use Abraham\TwitterOAuth\TwitterOAuth as TwitterOAuth;

class Feed extends Nn\Modules\Datatype\Datatype {
	
	protected $handle;
	protected $hashtag;
	protected $since;
	protected $until;

	public static $SCHEMA = array(
			'handle' => 'short_text',
			'hashtag' => 'short_text',
			'since' => 'integer',
			'until' => 'integer',
			'created_at' => 'float',
			'updated_at' => 'float'
		);

	public static $PARAMS = array(
			'service' => [
					'Facebook',
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
		} else {
			return false;
		}
	}

	public function posts() {
		return Post::find(['feed_id'=>$this->id]);
	}

	private function facebook() {
		$appId = '188474151324215';
		$secret = '8f4e8fb0f91b41252ba71390d484a4d9';
		$fb = new Facebook([
				'app_id' => $appId,
				'app_secret' => $secret,
				'default_graph_version' => 'v2.4'
			]);
		$fb->setDefaultAccessToken($appId.'|'.$secret);
		$params = '?limit=100&fields=type,source,place,picture,object_id,message,link,caption,created_time,attachments';
		if($this->since) $params .= '&since='.$this->since;
		if($this->until) $params .= '&until='.$this->until;
		// $result = $facebook->api($this->handle.'/feed','GET',array('limit'=>200,'since'=>$since));
		$response = $fb->get('/'.$this->handle.'/posts'.$params);
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
			if(strpos(strtolower($json_obj),'#'.$this->hashtag) !== false) {
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
					$post = Post::find(['uid'=>$uid,'feed_id'=>$this->id],1,null,false,null,false);
					if(!$post){
						$post = new Post();
						$post->attr('uid',$uid);
						$post->attr('visible',true);
						$post->attr('feed_id',$this->id);
						$post->attr('created_at',strtotime($obj['created_time']->date));
					}
					$json_obj = json_encode($obj);
					if($post->attr('content') != $json_obj) {
						$post->attr('content',$json_obj);
						$post->save();
					}
				}
			}
		}
		$next = $fb->next($edge);
		if($next) $this->parseFacebookResults($fb,$next);
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
		// die(print_r($result));
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
		require_once ROOT.DS.'vendor'.DS.'vimeo'.DS.'vimeo.php';
		$api_key = '67103ea122ffdf4b60d4d451ccbd13738e126e9a';
		$api_secret = 'f7f0fca1058b53c4d5e36561a945d7282e327f38';
		$vimeo = new \phpVimeo($api_key,$api_secret);
		$raw_result = $vimeo->call('vimeo.videos.getAll',array(
			'user_id' => $this->handle,
			'full_response' => 1
		));
		if(isset($raw_result->videos)) {
			$result = $raw_result->videos->video;
			$items = array();
			foreach ($result as $key => $value) {
				if(isset($value->tags)){
					$tagged = false;
					if(empty($this->hashtag)) {
						$tagged = true;
					} else {
						foreach($value->tags->tag as $tag) {
							if($tag->_content == $this->hashtag){
								$tagged = true;
								break;
							}
						}
					}
					if($tagged) {
						$uri_array = explode('/',$value->id);
						$uid = array_pop($uri_array);
						$post = Post::find(['uid'=>$uid,'feed_id'=>$this->id],1,null,false);
						if(!$post){
							$post = new Post();
							$post->attr('uid',$uid);
							$post->attr('visible',true);
							$post->attr('feed_id',$this->id);
							$post->attr('created_at',strtotime($value->upload_date));
						}
						$post->attr('content',json_encode($value));
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
				if(strpos(strtolower(serialize($value)),'#'.$this->hashtag) !== false) {
					$uid = $value['id'];
					$post = Post::find(['uid'=>$uid,'feed_id'=>$this->id],1,null,false);
					if(!$post){
						$post = new Post();
						$post->attr('uid',$uid);
						$post->attr('visible',true);
						$post->attr('feed_id',$this->id);
						$post->attr('created_at',strtotime($value['snippet']['publishedAt']));
					}
					$post->attr('content',json_encode($value));
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
					if(strpos(strtolower(serialize($value)),$this->hashtag) !== false) {
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