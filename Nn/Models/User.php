<?php

namespace Nn\Models;
use Nn;

class User extends Nn\Core\DataModel {

	protected static $_hashbrown = 'bajs';
	protected $id;
	protected $email;
	protected $password;
	protected $first_name;
	protected $last_name;
	protected $role_id;

	public static $SCHEMA = array(
			'email' => 'short_text',
			'password' => 'short_text',
			'first_name' => 'short_text',
			'last_name' => 'short_text',
			'role_id' => 'integer',
			'created_at' => 'integer',
			'updated_at' => 'integer'
		);
	
	public function __construct($first_name=null, $last_name=null, $email=null, $password=null, $role_id=null){
		if(!empty($email) && !empty($password)){
			$this->first_name = $first_name;
			$this->last_name = $last_name;
			$this->email = $email;
			$this->password = $this->hashify($password);
			$this->role_id = $role_id;
			return $this;
		} else {
			return false;
		}
	}

	public function fill($first_name=null, $last_name=null, $email=null, $password=null, $role_id=null){
		if(!empty($first_name)) $this->first_name = $first_name;
		if(!empty($last_name)) $this->last_name = $last_name;
		if(!empty($email)) $this->email = $email;
		if(!empty($password)) $this->password = $this->hashify($password);
		if(!empty($role_id)) $this->role_id = $role_id;
		return $this;
	}
		
	public static function authenticate($email="", $password=""){
		$query = array('email'=>$email,'password'=>(static::hashify($password)));
		$result = static::find($query,1);
		return $result;
	}

	private static function hashify($pw) {
		return sha1(static::$_hashbrown.$pw);
	}

	private static function checkHash($pwd) {
		$outcome = ($this->password === sha1($this->hashbrown.$pwd)) ? true : false;
		return $outcome;
	}
	
	public function full_name(){
		return $this->first_name . " " . $this->last_name;
	}

	public function role() {
		return Role::find($this->role_id);
	}
	
}

?>