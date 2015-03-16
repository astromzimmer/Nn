<?php

namespace Nn\Modules\User;
use Nn;

class Privilege extends Nn\Core\DataModel {
	
	protected $id;
	protected $section;
	protected $role_id;

	public static $SCHEMA = array(
			'section' => 'short_text',
			'role_id' => 'integer'
		);

	public function __construct($section=null, $role_id=null){
		if(isset($section) && isset($role_id)){
			$this->section = $section;
			$this->role_id = $role_id;
			return $this;
		} else {
			return false;
		}
	}
	
}

?>