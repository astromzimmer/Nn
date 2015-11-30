<?php

namespace Nn\Modules\Pair;
use Nn;

class Pair extends Nn\Modules\Datatype\Datatype {
	
	protected $id;
	protected $left;
	protected $right;

	public static $SCHEMA = array(
			'left' => 'short_text',
			'right' => 'text',
			'created_at' => 'integer',
			'updated_at' => 'integer'
		);

	public static $PARAMS = array(
			'left_format' => array('string','integer','float'),
			'right_format' => array('string','integer','float')
		);

	public function exportProperties($excludes=array()) {
		return array(
			'id'			=>	$this->id,
			'left'			=>	$this->left(),
			'right'			=>	$this->right,
		);
	}

	public function __construct($left=null,$right=null){
		if(!empty($left) && !empty($right)){
			$this->left = strtoupper(preg_replace("/[^a-zA-Z0-9s]/","_",$left));
			$this->right = $right;
			return $this;
		} else {
			return false;
		}
	}
	
	public function fill($left=null,$right=null){
		$this->left = $left;
		$this->right = $right;
		return $this;
	}

}

?>