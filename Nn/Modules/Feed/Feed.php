<?php

namespace Nn\Modules\Feed;
use Post;
use Nn;
use Utils;

use Facebook\FacebookSession as FacebookSession;
use Facebook\FacebookRequest as FacebookRequest;
use Facebook\GraphUser as GraphUser;
use Facebook\FacebookRequestException as FacebookRequestException;

class Feed extends Nn\Modules\Datatype\Datatype {
	
	protected $handle;
	protected $hashtag;
	protected $timeout;

	public static $SCHEMA = array(
			'attribute_id' => 'integer',
			'handle' => 'short_text',
			'hashtag' => 'short_text',
			'timeout' => 'integer',
			'created_at' => 'integer',
			'updated_at' => 'integer'
		);

	public function exportProperties($excludes=array()) {
		return array(
			'handle'		=>	$this->handle,
			'hashtag'		=>	$this->hashtag,
			'timeout'		=>	$this->timeout,
			'created_at'	=>	$this->created_at,
			'updated_at'	=>	$this->updated_at,
		);
	}
	
	public function __construct($handle=null,$hashtag=null,$timeout=null){
		if(!empty($handle)){
			$this->handle = $handle;
			$this->hashtag = $hashtag;
			$this->timeout = empty($timeout) ? (60*60*24*90) : $timeout;
			return $this;
		} else {
			return false;
		}
	}

	public function fetch() {
		$attribute = $this->attribute();
		$attributetype = $attribute->attributetype();
		$params = json_decode($attributetype->attr('params'),true);
		$service = strtolower($params['service']);
		$result = $this->$service();
		if($result) {
			$this->updated_at = time()*1000;
			return $this->save();
		} else {
			return false;
		}
	}

	public function posts() {
		return Post::find(['feed_id'=>$this->id]);
	}

	private function facebook() {
		// require_once ROOT.DS.'vendor'.DS.'Facebook'.DS.'facebook.php';
		// $facebook = new \Facebook(array(
		// 	'appId' => '188474151324215',
		// 	'secret' => '8f4e8fb0f91b41252ba71390d484a4d9'
		// ));
		$appId = '188474151324215';
		$secret = '8f4e8fb0f91b41252ba71390d484a4d9';
		FacebookSession::setDefaultApplication($appId,$secret);
		$fb_session = FacebookSession::newAppSession();
		$since = time() - $this->timeout;
		// $result = $facebook->api($this->handle.'/feed','GET',array('limit'=>200,'since'=>$since));
		$fb_request = new FacebookRequest($fb_session,'GET','/'.$this->handle.'/feed',array('limit'=>200,'since'=>$since));
		$response = $fb_request->execute();
		$result = $response->getGraphObjectList();
		if(isset($result)) {
			// $items = array();
			foreach ($result as $key => $value) {
				$obj = $value->asArray();
				if(strpos(strtolower(serialize($value)),'#'.$this->hashtag) !== false) {
					if(isset($obj['message']) || isset($obj['name'])) {
						// if($value['type'] != 'status' && strpos(strtolower(serialize($value)),'#sdc2013') !== false) {
						// if(strpos(strtolower(serialize($value)),'#sdc2013') !== false) {
						// Check for photo and attach higher res URL if available
						if($obj['type'] == 'photo') {
							$photo_request = new FacebookRequest($fb_session,'GET','/'.$obj['object_id']);
							$response = $photo_request->execute();
							$photo = $response->getGraphObject()->asArray();
							$obj['picture'] = $photo['images'][2]->source;
						}
						$uid = $obj['id'];
						$post = Post::find(['uid'=>$uid,'feed_id'=>$this->id],1,null,false,null,false);
						if(!$post){
							$post = new Post();
							$post->attr('uid',$uid);
							$post->attr('visible',true);
							$post->attr('feed_id',$this->id);
							$post->attr('created_at',strtotime($obj['created_time']));
						}
						$json_obj = json_encode($obj);
						if($post->attr('content') != $json_obj) {
							$post->attr('content',$json_obj);
							$post->save();
						}
					}
				}
			}
			return true;
		} else {
			return false;
		}
	}

	private function twitter() {
		require_once ROOT.DS.'vendor'.DS.'twitteroauth'.DS.'twitteroauth.php';
		$consumer_key = 'mm6dM0e47IinHYOViQ9pg';
		$consumer_secret = 'GKpeQglxnnUk9zNSaXd56gG2ir22WRB2NDW9ntFGSY';
		$access_token = '377840296-VicUNH5cWYHkjtPgZTzQhYothTI4QygzrBdHfbMO';
		$access_token_secret = 'Os7zGput5SsLRDAX5jMBVQewT9WnsAgmlzcgycsYK9E';
		$twitter = new \TwitterOauth($consumer_key,$consumer_secret,$access_token,$access_token_secret);
		$result = $twitter->get("https://api.twitter.com/1.1/search/tweets.json?q=from%3A{$this->handle}%20%23{$this->hashtag}%20since%3A2011-01-01");
		// $result = $twitter->get("https://api.twitter.com/1.1/search/tweets.json?q=from%3A{$this->handle}%20since%3A2011-01-01");
		if($items = $result->statuses) {
			foreach ($items as $value) {
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
		$channels_url = "https://www.googleapis.com/youtube/v3/channels?part=contentDetails&forUsername={$this->handle}&key={$key}";
		// $channels_url = "https://www.googleapis.com/youtube/v3/channels?part=contentDetails&id={$this->handle}&key={$key}";
		$channels = json_decode(Utils::getURL($channels_url),true);
		if(count($channels['items']) == 0) return false;
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
		$items = array();
		if(!isset($result['errors'])){
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
		if($items = $result['posts']) {
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