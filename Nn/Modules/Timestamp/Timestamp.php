<?php

namespace Nn\Modules\Timestamp;
use Nn;

class Timestamp extends Nn\Core\Datatype {
	
	public $id;
	public $timestamp;
	public $created_at;
	public $updated_at;

	public static $SCHEMA = array(
			'timestamp' => 'integer',
			'created_at' => 'integer',
			'updated_at' => 'integer'
		);

	public function exportProperties($excludes=array()) {
		return array(
			'id'			=>	$this->id,
			'timestamp'		=>	$this->timestamp*1000,
			'created_at'	=>	$this->created_at,
			'updated_at'	=>	$this->updated_at,
		);
	}
	
	public function datetime() {
		return strftime(DATE_FORMAT,$this->timestamp);
	}
	
	public function fill($timestamp=null){
		$this->timestamp = $timestamp;
		return $this;
	}

}

?>