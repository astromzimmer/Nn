<?php

namespace Nn\Core;
use Nn\Models\User as User;
use Nn;

class Session {

	private $logged_in;
	private $user_id;
	public $flash;
	private $referer;
	
	function __construct(){
		if(!@session_start()) {
			session_regenerate_id(true);
			session_start();
		}
	}

	public function init() {
		$this->retrieveFlash();
		$this->check_referer();
		$this->check_login();
		
		if($this->logged_in){
//			if logged in stuff
			define('SESSION_HEADER', 'admin_header');
			define('SESSION_FOOTER', 'admin_footer');
			Nn::settings('ADMIN_AREA', true);
		} else {
//			if not logged in stuff
			define('SESSION_HEADER', 'header');
			define('SESSION_FOOTER', 'footer');
		}
	}
	
	public function is_logged_in(){
		return $this->logged_in;
	}

	public function user_id() {
		return $this->user_id;
	}
	
	public function login($user){
		if($user){
			$this->user_id = $_SESSION['user_id'] = $user->attr('id');
			$this->logged_in = true;
		}
	}
	
	public function logout(){
		unset($_SESSION['user_id']);
		unset($this->user_id);
		$this->logged_in = false;
	}
	
	public function flash($fsh=null) {
		if(isset($fsh)) {
			if(!$this->flash) $this->flash = [];
			array_push($this->flash,$fsh);
			$_SESSION['flash'] = $this->flash;
		} else {
			if($this->flash) {
				return $this->flash;
			} else {
				return false;
			}
		}
	}

	public function referer() {
		return $this->referer;
	}
	
	private function check_login(){
		if(isset($_SESSION['user_id']) && User::find($_SESSION['user_id'])){
			$this->user_id = $_SESSION['user_id'];
			$this->logged_in = true;
		} else {
			unset($this->user_id);
			$this->logged_in = false;
		}
	}

	private function retrieveFlash() {
		if(isset($_SESSION['flash'])) {
			$this->flash = $_SESSION['flash'];
			unset($_SESSION['flash']);
		} else {
			$this->flash = false;
		}
	}

	private function check_referer() {
		if(isset($_SERVER['HTTP_REFERER'])) {
			$this->referer = $_SERVER['HTTP_REFERER'];
			unset($_SERVER['HTTP_REFERER']);
		} else {
			$this->referer = false;
		}
	}

}

?>