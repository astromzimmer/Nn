<?php

namespace Nn\Trackers;
use Nn;

class Visitor extends Nn\Core\DataModel {
	
	protected $ip;
	protected $user_agent;
	protected $referrer;

	public static $SCHEMA = array(
			'ip' => 'short_text',
			'user_agent' => 'text',
			'referrer' => 'text',
			'created_at' => 'float',
			'updated_at' => 'float'
		);

	public function exportProperties($excludes=array()) {
		return array(
			'id'			=>	$this->id,
			'ip'			=>	$this->ip,
			'user_agent'	=>	$this->user_agent,
			'referrer'		=>	$this->referrer,
		);
	}

	public function __construct($ip=null,$user_agent=null,$referrer=null){
		if(!isset($ip)) return false;
		$this->ip = $ip;
		$this->user_agent = $user_agent;
		$this->referrer = $referrer;
		return $this;
	}

}

?>