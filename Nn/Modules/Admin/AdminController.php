<?php

namespace Nn\Modules\Admin;
use Nn\Modules\User\User as User;
use Nn\Modules\Node\Node as Node;
use Nn\Core\Mailer as Mailer;
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

	function forgot() {

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

	function reset_password() {
		$this->renderMode(false);
		$email = $_POST['uid'];
		if($user = User::find(['email'=>$email],1)){
			$new_password = $user->resetPassword();

			$message = "Your password for ".Nn::settings('DOMAIN')." has been reset: \r\n\r\n".$new_password;
			$message .= "\r\n\r\nPlease log in and change it as soon as possible, to avoid security breaches.";
			
			$mailer = new Mailer();
		
			$mailer->Subject = Nn::settings('DOMAIN').': Password reset';
			$mailer->Body = $message;
			$mailer->isHTML = false;
			// $mailer->AltBody = $message;
			
			$mailer->AddAddress($email);
			
			if(!$mailer->Send()) {
				$result = false;
			} else {
				$result = true;
			}
			$mailer->ClearAddresses();
			$mailer->ClearAttachments();

			Nn::flash(['success'=>Nn::babel('New password send to').' '.$email]);
			Utils::redirect_to('/admin/login');
		} else {
			Nn::flash(['error'=>Nn::babel('No valid account found for that email')]);
			Utils::redirect_to('/admin/forgot');
		}
	}
	
	function after() {
	
	}
}

?>