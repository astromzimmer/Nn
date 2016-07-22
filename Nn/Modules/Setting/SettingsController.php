<?php

namespace Nn\Modules\Setting;
use Nn\Modules\Node\Node as Node;
use Nn;
use Utils;

class SettingsController extends Nn\Core\Controller {
	
	function before() {
		Nn::authenticate();
	}
	
	function index() {
		$this->setTemplateVars([
				'settings'=> Setting::find_all()
			]);
	}
	
	function view($id=null) {
		$this->setTemplateVars([
				'setting'=> Setting::find($id)
			]);
	}

	function make() {
		$this->setTemplateVars([
				'settings'=> Setting::find_all()
			]);
		$this->setTemplateVars([
				'setting'=> new Setting()
			]);
	}
	
	function create() {
		$setting = new Setting($_POST['name'],$_POST['value'],$_POST['description']);
		if($setting->save()) {
			Utils::redirect(DOMAIN.DS.'admin'.DS.'settings');
		} else {
			die("failed to create setting");
		}
	}

	function edit($id=null) {
		$this->setTemplateVars([
				'settings'=> Setting::find_all()
			]);
		$this->setTemplateVars([
				'setting'=> Setting::find($id)
			]);
	}
	
	function update($id=null) {
		$setting = Setting::find($id);
		$setting = $setting->fill($_POST['name'],$_POST['value'],$_POST['description']);
		if($setting->save()) {
			Utils::redirect(DOMAIN.DS.'admin'.DS.'settings');
		} else {
			die("failed to update setting");
		}
	}
	
	function delete($id=null) {
		$setting = Setting::find($id);
		if($setting->delete()) {
			Utils::redirect(DOMAIN.DS.'admin'.DS.'settings');
		}
	}
}

?>