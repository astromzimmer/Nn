<?php

namespace Nn\Modules\User;
use Nn;
use Utils;

class Role extends Nn\Core\DataModel {
	
	protected $name;

	public static $SCHEMA = array(
			'name' => 'short_text',
			'created_at' => 'float',
			'updated_at' => 'float'
		);
	
	public function __construct($name=null){
		if(isset($name)){
			$this->name = $name;
			return $this;
		} else {
			return false;
		}
	}

	public function users () {
		return User::find(array('role_id'=>$this->id));
	}
	
	public function privileges() {
		$query = array('role_id'=>$this->role_id);
		$result = Privilege::find($query);
		return $result;
	}
	
}

?>