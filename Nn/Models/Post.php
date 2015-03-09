<?php

namespace Nn\Models;
use Nn\Models\Feed as Feed;
use Nn;

class Post extends Nn\Core\DataModel {
	
	protected $uid;
	protected $content;
	protected $visible;

	public static $SCHEMA = array(
			'feed_id' => 'integer',
			'uid' => 'short_text',
			'content' => 'long_text',
			'visible' => 'integer',
			'created_at' => 'integer',
			'updated_at' => 'integer'
		);

	public function exportProperties($excludes=array()) {
		return array(
			'id'			=>	$this->id,
			'uid'			=>	$this->feed_id.'_'.$this->uid,
			'source'		=>	$this->service(),
			'content'		=>	$this->content(),
			'created_at'	=>	$this->created_at,
			'updated_at'	=>	$this->updated_at
		);
	}
	
	public function content() {
		return json_decode($this->content);
	}
	
	public function tagged_content() {
		return tagged($this->content());
	}
	
	public function __construct($uid=null,$content=null){
		if(!empty($uid) && !empty($content)){
			$this->uid = $uid;
			$this->content = htmlspecialchars(str_replace("\n", "", $content));
			return $this;
		} else {
			return false;
		}
	}

	public function service() {
		return Feed::find($this->feed_id,1)->attributetype()->params()['service'];
	}

}

?>