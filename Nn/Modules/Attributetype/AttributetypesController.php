<?php

namespace Nn\Modules\Attributetype;
use Nn\Modules\Node\Node as Node;
use Nn\Modules\Attributetype\Attributetype;
use Nn\Modules\Datatype\Datatype as Datatype;
use Nn;
use Utils;

class AttributetypesController extends Nn\Core\Controller {
	
	function before() {
		Nn::authenticate();
	}
	
	function index() {
		$this->setTemplateVars([
				'attributetypes'=> Attributetype::find_all(null,'position')
			]);
	}
	
	function view($id=null) {
		$this->setTemplateVars([
				'attributetypes'=> Attributetype::find_all(null,'position')
			]);
		$this->setTemplateVars([
				'attributetype'=> Attributetype::find($id)
			]);
	}
	
	function sort() {
		$this->renderMode('raw');
		$attributetypes = $_POST['attributetypes'];
		for($i = 0; $i < count($attributetypes); $i++) {
			$attributetype = Attributetype::find($attributetypes[$i]);
			$attributetype->attr('position',$i);
			if(!$attributetype->save()) {
				Utils::sendResponseCode(500);
			}
		}
		Utils::sendResponseCode(200);
	}
	
	function make() {
		foreach(glob(ROOT.DS.'Nn'.DS.'Models'.DS.'*') as $model_file) {
			if(substr($model_file,-4,4) == '.php') {
				include_once $model_file;
			}
		}
		foreach(glob(ROOT.DS.'App'.DS.'Models'.DS.'*') as $model_file) {
			if(substr($model_file,-4,4) == '.php') {
				include_once $model_file;
			}
		}
		$this->setTemplateVars([
				'attributetypes'=> Attributetype::find_all(null,'position'),
				'datatypes'=> Utils::getSubclassesOf('Nn\Core\Datatype',true)
			]);
	}
	
	function create() {
		$params = array_diff_key($_POST,array('name'=>null,'datatype'=>null,'submit'=>null));
		$attributetype = new Attributetype($_POST['name'],$_POST['datatype'],$params);
		if($attributetype->save()) {
			Utils::redirect_to(DOMAIN.DS.'admin'.DS.'attributetypes');
		} else {
			die("failed to create attributetype");
		}
	}
	
	function edit($id=null) {
		foreach(glob(ROOT.DS.'Nn'.DS.'Models'.DS.'*') as $model_file) {
			if(substr($model_file,-4,4) == '.php') {
				include_once $model_file;
			}
		}
		foreach(glob(ROOT.DS.'App'.DS.'Models'.DS.'*') as $model_file) {
			if(substr($model_file,-4,4) == '.php') {
				include_once $model_file;
			}
		}
		$this->setTemplateVars([
				'attributetypes'=> Attributetype::find_all(null,'position'),
				'attributetype'=> Attributetype::find($id),
				'datatypes'=> Utils::getSubclassesOf('Nn\Core\Datatype',true)
			]);
	}
	
	function update($id=null) {
		$params = array_diff_key($_POST,array('name'=>null,'datatype'=>null,'submit'=>null));
		$attributetype = Attributetype::find($id);
		$attributetype->fill($_POST['name'],$_POST['datatype'],$params);
		if($attributetype->save()) {
			Utils::redirect_to(DOMAIN.DS.'admin'.DS.'attributetypes');
		} else {
			die("failed to update attribute type");
		}
	}
	
	function delete($id=null) {
		$attributetype = Attributetype::find($id);
		if($attributetype->delete()) {
			Utils::redirect_to(DOMAIN.DS.'admin'.DS.'attributetypes');
		} else {
			die("failed to remove attributetype registration");
		}
	}
}

?>