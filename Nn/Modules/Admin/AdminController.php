<?php

namespace Nn\Modules\Admin;
use Nn\Modules\User\User as User;
use Nn\Modules\Node\Node as Node;
use Nn;
use Utils;

class AdminController extends Nn\Core\Controller {
	
	function before() {
		
	}

	function try_to_login() {
		$this->renderMode(false);
		$user = User::authenticate($_POST['uid'], $_POST['pwd']);
		if($user) {
			Nn::session()->login($user);
			$first_name = $user->attr('first_name');
			$suffix = (empty($first_name)) ? '!' : ' '.$first_name.'!';
			Nn::flash(['info'=>Nn::babel('Welcome').$suffix]);
			# TODO
			// if(Nn::referer() != '') {
			// 	Utils::redirect_to(Nn::referer());
			// } else {
			if(Nn::storage()->backup('automatic_backup')) {
				Nn::flash(['success'=>Nn::babel('Database backed up successfully')]);
			} else {
				Nn::flash(['error'=>Nn::babel('Error! Please contact site admin')]);
			}
			Utils::redirect_to(Nn::settings('DOMAIN').'/admin/index');
			// }
		} else {
			Nn::flash(['error'=>Nn::babel('Wrong username/password – please try again')]);
			Utils::redirect_to(Nn::settings('DOMAIN').'/admin/login');
		}
	}
	
	function login() {

	}

	function index() {
		Nn::authenticate();
		$this->setTemplateVars([
				'index'=>true
			]);
	}

	function backup_db() {
		$this->renderMode(false);
		Nn::authenticate();
		if(Nn::storage()->backup()) {
			Nn::flash(['success'=>Nn::babel('Database backed up successfully')]);
		} else {
			Nn::flash(['error'=>Nn::babel('Error! Please contact site admin')]);
		}
		Utils::redirect_to(Nn::referer());
	}

	function flush_cache() {
		$this->renderMode(false);
		Nn::authenticate();
		if(Nn::cache()->flush()) {
			Nn::flash(['success'=>Nn::babel('Cache flushed successfully')]);
		} else {
			Nn::flash(['error'=>Nn::babel('Error! Please contact site admin')]);
		}
		Utils::redirect_to(Nn::referer());
	}
	
	function logout() {
		$this->renderMode(false);
		Nn::session()->logout();
		Nn::flash(['success'=>Nn::babel('Successfully logged out')]);
		Utils::redirect_to('/');
	}
	
	function after() {
	
	}
}

?>