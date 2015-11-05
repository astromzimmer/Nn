<?php

namespace Nn\Modules\Keyval;
use Nn;

class Keyval extends Nn\Modules\Datatype\Datatype {
	
	protected $id;
	protected $key;
	protected $value;

	public static $SCHEMA = array(
			'key' => 'short_text',
			'value' => 'text',
			'created_at' => 'integer',
			'updated_at' => 'integer'
		);

	public static $PARAMS = array(
			'key_format' => array('string','integer','float'),
			'value_format' => array('string','integer','float')
		);

	public function exportProperties($excludes=array()) {
		return array(
			'id'			=>	$this->id,
			'key'			=>	$this->key(),
			'value'			=>	$this->value,
		);
	}

	public function __construct($key=null,$value=null){
		if(!empty($key) && !empty($value)){
			$this->key = strtoupper(preg_replace("/[^a-zA-Z0-9s]/","_",$key));
			$this->value = $value;
			return $this;
		} else {
			return false;
		}
	}
	
	public function fill($key=null,$value=null){
		$this->key = $key;
		$this->value = $value;
		return $this;
	}

}

?>