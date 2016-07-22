<?php

namespace Nn\Modules\Attributetype;
use Nn\Modules\Node\Node as Node;
use Nn\Modules\Attribute\Attribute as Attribute;
use Nn\Modules\Datatype\Datatype as Datatype;
use Nn;
use Utils;

class AttributetypesController extends Nn\Core\Controller {
	
	function before() {
		Nn::authenticate();
	}

	function _params($datatype) {
		$this->renderMode('partial');
		$datatype_class = 'Nn\\Modules\\'.$datatype.'\\'.$datatype;
		$params = $datatype_class::$PARAMS;
		$this->setTemplateVars([
				'params'=> $params
			]);
	}

	function _default($datatype) {
		$this->renderMode('partial');
		$datatype_class = 'Nn\\Modules\\'.$datatype.'\\'.$datatype;
		$default = $datatype_class::$DEFAULT;
		$this->setTemplateVars([
				'default'=> $default
			]);
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
				'datatypes'=> Utils::getSubclassesOf('Nn\Modules\Datatype\Datatype',true)
			]);
	}
	
	function create() {
		$params = (isset($_POST['params'])) ? $_POST['params'] : null;
		$default_value = (isset($_POST['default_value'])) ? $_POST['default_value'] : null;
		$attributetype = new Attributetype($_POST['name'],$_POST['datatype'],$params,$default_value);
		if($attributetype->save()) {
			Utils::redirect(DOMAIN.'/admin/attributetypes');
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
		$attributetype = Attributetype::find($id);
		$datatype = $attributetype->attr('datatype');
		$datatype_class = 'Nn\\Modules\\'.$datatype.'\\'.$datatype;
		$datatype_params = $datatype_class::$PARAMS;
		$datatype_default = $datatype_class::$DEFAULT;
		$this->setTemplateVars([
				'attributetypes'=> Attributetype::find_all(null,'position'),
				'attributetype'=> $attributetype,
				'datatypes'=> Utils::getSubclassesOf('Nn\Modules\Datatype\Datatype',true),
				'datatype_params'=> $datatype_params,
				'datatype_default'=> $datatype_default
			]);
	}
	
	function update($id=null) {
		$params = (isset($_POST['params'])) ? $_POST['params'] : null;
		$default_value = (isset($_POST['default_value'])) ? $_POST['default_value'] : null;
		$attributetype = Attributetype::find($id);
		$attributetype->fill($_POST['name'],$_POST['datatype'],$params,$default_value);
		if($attributetype->save()) {
			Utils::redirect(DOMAIN.'/admin/attributetypes');
		} else {
			die("failed to update attribute type");
		}
	}
	
	function delete($id=null) {
		$attributes = Attribute::find(['attributetype_id'=>$id]);
		if($attributes) {
			Nn::flash(['error'=>Nn::babel('There are '.count($attributes).' attribute(s) of this type. Please remove before continuing.')]);
			Utils::redirect(DOMAIN.'/admin/attributetypes/edit/'.$id);
		} else {
			$attributetype = Attributetype::find($id);
			if(!$attributetype->delete()) {
				Nn::flash(['error'=>Nn::babel('Something went wrong. Please contact site admin.')]);
				Utils::redirect(DOMAIN.'/admin/attributetypes/edit/'.$id);
			}
			Utils::redirect(DOMAIN.'/admin/attributetypes');
		}
	}
}

?>