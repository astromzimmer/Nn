<?php

namespace Nn\Modules\Integer;
use Nn;

class Integer extends Nn\Modules\Datatype\Datatype {
	
	protected $id;
	protected $number;
	protected $created_at;
	protected $updated_at;

	public static $SCHEMA = array(
			'number' => 'integer',
			'created_at' => 'float',
			'updated_at' => 'float'
		);

	public static $PARAMS = array(
			'format' => array('raw','timestamp')
		);

	public function exportProperties($excludes=array()) {
		return array(
			'id'			=>	$this->id,
			'number'		=>	$this->number,
			'timestamp'		=>	$this->number*1000,
			'created_at'	=>	$this->created_at,
			'updated_at'	=>	$this->updated_at,
		);
	}
	
	public function datetime() {
		return strftime(DATE_FORMAT,$this->number);
	}

}

?>