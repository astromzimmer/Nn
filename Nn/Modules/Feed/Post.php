<?php

namespace Nn\Modules\Feed;
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
			'created_at' => 'float',
			'updated_at' => 'float'
		);

	public function exportProperties($excludes=array()) {
		return array(
			'id'			=>	$this->id,
			'uid'			=>	$this->feed_id.'_'.$this->uid,
			'source'		=>	$this->service(),
			'node'			=>	[
									'id'	=> $this->node()->attr('id'),
									'slug'	=> $this->node()->attr('id').'-'.$this->node()->slug(),
									'title'	=> $this->node()->title()
								],
			'content'		=>	$this->content(),
			'timestamp'		=>	$this->created_at,
			'created_at'	=>	$this->created_at,
			'updated_at'	=>	$this->updated_at,
			'created_year'	=>	date('Y',$this->created_at),
			'created_week'	=>	idate('W',$this->created_at)
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

	public function feed() {
		return Feed::find($this->feed_id,1);
	}

	public function node() {
		return $this->feed()->attribute()->node();
	}

	public function service() {
		return $this->feed()->attributetype()->params()['service'];
	}

}

?>