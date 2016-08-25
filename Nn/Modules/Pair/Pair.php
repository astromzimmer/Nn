<?php

namespace Nn\Modules\Pair;
use Nn;

class Pair extends Nn\Modules\Datatype\Datatype {
	
	protected $rkey;
	protected $lval;

	public static $SCHEMA = array(
			'rkey' => 'short_text',
			'lval' => 'text',
			'created_at' => 'double',
			'updated_at' => 'double'
		);

	public static $PARAMS = array(
			'rkey_format' => array('string','integer','float'),
			'lval_format' => array('string','integer','float')
		);

	public function exportProperties() {
		return array(
			'id'			=>	$this->id,
			'rkey'			=>	$this->rkey,
			'lval'			=>	$this->lval,
		);
	}

	public function __construct($rkey=null,$lval=null){
		if(!empty($rkey) && !empty($lval)){
			$this->rkey = strtoupper(preg_replace("/[^a-zA-Z0-9s]/","_",$rkey));
			$this->lval = $lval;
			return $this;
		} else {
			return false;
		}
	}
	
	public function fill($rkey=null,$lval=null){
		$this->rkey = $rkey;
		$this->lval = $lval;
		return $this;
	}

}

?>