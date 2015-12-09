<?php

namespace Nn\Modules\Print;
use Nn\Modules\Node\Node as Node;
use Nn;
use Utils;

class PrintsController extends Nn\Core\Controller {
	
	function before() {
		Nn::authenticate();
	}
	
	function index() {
		$this->setTemplateVars([
				'settings'=> Print::find_all()
			]);
	}
	
	function view($id=null) {
		$this->setTemplateVars([
				'setting'=> Print::find($id)
			]);
	}

	function make() {
		$this->setTemplateVars([
				'settings'=> Print::find_all()
			]);
		$this->setTemplateVars([
				'setting'=> new Print()
			]);
	}
	
	function create() {
		$setting = new Print($_POST['name'],$_POST['value'],$_POST['description']);
		if($setting->save()) {
			Utils::redirect_to(DOMAIN.DS.'admin'.DS.'settings');
		} else {
			die("failed to create setting");
		}
	}

	function edit($id=null) {
		$this->setTemplateVars([
				'settings'=> Print::find_all()
			]);
		$this->setTemplateVars([
				'setting'=> Print::find($id)
			]);
	}
	
	function update($id=null) {
		$setting = Print::find($id);
		$setting = $setting->fill($_POST['name'],$_POST['value'],$_POST['description']);
		if($setting->save()) {
			Utils::redirect_to(DOMAIN.DS.'admin'.DS.'settings');
		} else {
			die("failed to update setting");
		}
	}
	
	function delete($id=null) {
		$setting = Print::find($id);
		if($setting->delete()) {
			Utils::redirect_to(DOMAIN.DS.'admin'.DS.'settings');
		}
	}
}

?>