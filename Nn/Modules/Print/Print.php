<?php

namespace Nn\Modules\Print;
use Nn;

class Print extends Nn\Core\DataModel {
	
	protected $id;
	protected $name;
	protected $value;
	protected $description;

	public static $SCHEMA = array(
			'name' => 'short_text',
			'value' => 'text',
			'description' => 'text',
			'created_at' => 'integer',
			'updated_at' => 'integer'
		);

	public function exportProperties($excludes=array()) {
		return array(
			'id'			=>	$this->id,
			'name'			=>	$this->name(),
			'value'			=>	$this->value,
			'description'	=>	$this->description,
		);
	}

	public function __construct($name=null,$value=null,$description=null){
		if(!empty($name) && !empty($value)){
			$this->name = strtoupper(preg_replace("/[^a-zA-Z0-9s]/","_",$name));
			$this->value = $value;
			$this->description = $description;
			return $this;
		} else {
			return false;
		}
	}

	public function description() {
		return $this->description;
	}
	
	public function fill($name=null,$value=null,$description=null){
		$this->name = $name;
		$this->value = $value;
		$this->description = $description;
		return $this;
	}

}

?>