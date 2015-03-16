<?php

namespace Nn\Modules\User;
use Nn\Modules\Node\Node as Node;
use Nn\Modules\User\User as User;
use Nn\Modules\User\Role as Role;
use Nn;
use Utils;

class UsersController extends Nn\Core\Controller {
	
	function before() {
		Nn::authenticate();
	}
	
	function index() {
		if(Nn::authenticated('admins')) {
			$this->setTemplateVars([
					'roles'=> Role::find_all()
				]);
		} else {
			$this->setTemplateVars([
					'roles'=> Role::find(array('name'=>Nn::authenticated()))
				]);
		}
	}
	
	function view($id=null) {
		
	}

	function manage_role($id=null) {
		if(isset($id)) {
			$this->setTemplateVars([
					'role'=> Role::find($id)
				]);
		}
		if(Nn::authenticated('admins')) {
			$this->setTemplateVars([
					'roles'=> Role::find_all()
				]);
		} else {
			$this->setTemplateVars([
					'roles'=> Role::find(array('name'=>Nn::authenticated()))
				]);
		}
	}

	function create_role() {
		$role = new Role();
		$role->attr('name',$_POST['name']);
		if($role->save()) {
			Nn::flash(['success'=>Nn::babel('User role created successfully')]);
			Utils::redirect_to(DOMAIN.DS.'admin'.DS.'users');
		} else {
			Nn::flash(['error'=>Nn::babel('Error! Please contact site admin')]);
			Utils::redirect_to(DOMAIN.DS.'admin'.DS.'users'.DS.'manage_role');
		}
	}
	
	function update_role($id=null) {
		$role = Role::find($id);
		$role->attr('name',$_POST['name']);
		if($role->save()) {
			Nn::flash(['success'=>Nn::babel('User role updated successfully')]);
			Utils::redirect_to(DOMAIN.DS.'admin'.DS.'users');
		} else {
			Nn::flash(['error'=>Nn::babel('Error! Please contact site admin')]);
			Utils::redirect_to(DOMAIN.DS.'admin'.DS.'users'.DS.'manage_role'.DS.$role->attr('id'));
		}
	}
	
	function delete_role($id=null) {
		$role = Role::find($id);
		if($role->delete()) {
			Utils::redirect_to(DOMAIN.DS.'admin'.DS.'users');
		}
	}
	
	function manage($id=null,$role_id=null) {
		if(isset($id) && $id != 'in') {
			$user = User::find($id);
		} else {
			$user = new User();
			$user->attr('role_id',$role_id);
		}
		$this->setTemplateVars([
				'user'=> $user
			]);
		if(isset($role_id)) {
			$role = Role::find($role_id);
		} else {
			$role = $user->role();
		}
		$this->setTemplateVars([
				'role'=> $role
			]);
		if(Nn::authenticated('admins')) {
			$this->setTemplateVars([
					'roles'=> Role::find_all()
				]);
		} else {
			$this->setTemplateVars([
					'roles'=> Role::find(array('name'=>Nn::authenticated()))
				]);
		}
	}
	
	function create() {
		$user = new User($_POST['first_name'], $_POST['last_name'], $_POST['uid'], $_POST['pwd'], $_POST['role_id']);
		if($user->save()) {
			Nn::flash(['success'=>Nn::babel('User created successfully')]);
			Utils::redirect_to(DOMAIN.DS.'admin'.DS.'users');
		} else {
			Nn::flash(['error'=>Nn::babel('Error! Please contact site admin')]);
			Utils::redirect_to(DOMAIN.DS.'admin'.DS.'users'.DS.'manage'.DS.$user->attr('id'));
		}
	}
	
	function update($id=null) {
		$user = User::find($id);
		$user->fill($_POST['first_name'], $_POST['last_name'], $_POST['uid'], $_POST['pwd']);
		if($user->save()) {
			Nn::flash(['success'=>Nn::babel('User updated successfully')]);
			Utils::redirect_to(DOMAIN.DS.'admin'.DS.'users');
		} else {
			Nn::flash(['error'=>Nn::babel('Error! Please contact site admin')]);
			Utils::redirect_to(DOMAIN.DS.'admin'.DS.'users'.DS.'manage'.DS.$user->attr('id'));
		}
	}
	
	function delete($id=null) {
		$user = User::find($id);
		if($user->delete()) {
			Utils::redirect_to(DOMAIN.DS.'admin'.DS.'users');
		}
	}
	
	function after() {
	
	}
}

?>