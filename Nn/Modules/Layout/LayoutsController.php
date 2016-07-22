<?php

namespace Nn\Modules\Layout;
use Nn;
use Utils;

class LayoutsController extends Nn\Core\Controller {

	function before() {
		Nn::authenticate();
	}
	
	function index() {
		$this->setTemplateVars([
				'layouts'=> Layout::find_all(null,'position')
			]);
	}
	
	function sort() {
		$this->renderMode('raw');
		$layouts = $_POST['layouts'];
		for($i = 0; $i < count($layouts); $i++) {
			$layout = Layout::find($layouts[$i]);
			$layout->attr('position',$i);
			if(!$layout->save()) {
				Utils::sendResponseCode(500);
			}
		}
		Utils::sendResponseCode(200);
	}
	
	function make() {
		$this->setTemplateVars([
				'layouts'=> Layout::find_all(null,'position')
			]);
	}
	
	function create() {
		$name = !empty($_POST['name']) ? $_POST['name'] : null;
		$rules = !empty($_POST['rules']) ? $_POST['rules'] : null;
		$template = !empty($_POST['template']) ? $_POST['template'] : null;
		$layout = new Layout($name,$rules,$template);
		if($layout->save()) {
			Utils::redirect(DOMAIN.'/admin/layouts');
		} else {
			Nn::flash(['error'=>Nn::babel('Please fill in all fields')]);
			Utils::redirect(DOMAIN.'/admin/layouts/make');
		}
	}
	
	function edit($id=null) {
		$this->setTemplateVars([
				'layouts'=> Layout::find_all(null,'position'),
				'layout'=> Layout::find($id)
			]);
	}
	
	function update($id=null) {
		$layout = Layout::find($id);
		if(!empty($_POST['name'])) $layout->attr('name', $_POST['name']);
		if(!empty($_POST['rules'])) $layout->attr('rules', $_POST['rules']);
		if(!empty($_POST['template'])) $layout->attr('template', $_POST['template']);
		if($layout->save()) {
			Utils::redirect(DOMAIN.'/admin/layouts');
		} else {
			Nn::flash(['error'=>Nn::babel("Oups! Error. We'll have a look.")]);
			Utils::redirect(DOMAIN.'/admin/layouts/edit/'.$layout->attr('id'));
		}
	}
	
	function delete($id=null) {
		$layout = Layout::find($id);
		if($layout->delete()) {
			Utils::redirect(DOMAIN.'/admin/layouts');
		} else {
			Nn::flash(['error'=>Nn::babel("Oups! Error. We'll have a look.")]);
			Utils::redirect(DOMAIN.'/admin/layouts/edit/'.$layout->attr('id'));
		}
	}
}

?>