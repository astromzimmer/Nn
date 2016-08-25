<?php

namespace Nn\Modules\Table;
use Nn\Modules\Attribute\Attribute as Attribute;
use Nn;
use Utils;

class TablesController extends Nn\Core\Controller {
	
	function before() {
		Nn::authenticate();
	}
	
	function index() {
		$this->setTemplateVars([
				'tables'=> Table::find_all()
			]);
	}
	
	function view($id=null) {
		$this->setTemplateVars([
				'table'=> Table::find($id)
			]);
	}
	
	function make() {
		
	}
	
	function edit($id=null) {
		if(isset($id)) {
			$this->setTemplateVars([
					'table'=> Table::find($id)
				]);
		} else {
			$this->setTemplateVars([
					'table'=> new Table()
				]);
		}
	}
	
	function create() {
		$node_id = $_POST['node_id'];
		$table = new Table();
		if($table->make($_POST['title'], $_POST['description'], $_FILES['file_upload'])) {
			if($table->save()) {
				$attribute = new Attribute($node_id,$_POST['atype_id'],$table->attr('id'));
				if($attribute->save()) {
					Utils::redirect(Nn::settings('DOMAIN').'/admin/nodes/'.Nn::settings('NODE_VIEW').'/'.$node_id);
				} else {
					$table->delete();
					Nn::flash(['error'=>Nn::babel('Failed to register attribute')]);
					Utils::redirect(Nn::referer());
				}
			}
		} else {
			$attribute->delete();
			Nn::flash(['error'=>$table->errors()[0]]);
			Utils::redirect(Nn::referer());
		}
	}
	
	function update($id=null) {
		$table = Table::find($id);
		$table->attr('title',$_POST['title']);
		$table->attr('description',$_POST['description']);
		$node_id = $_POST['node_id'];
		if($table->save()) {
			# Attribute save needs error handling - yawn
			$attribute = $table->attribute();
			$attributetype_id = $_POST['attributetype_id'];
			$attribute->attr('attributetype_id',$attributetype_id);
			$attribute->save();
			Utils::redirect(Nn::settings('DOMAIN').'/admin/nodes/'.Nn::settings('NODE_VIEW').'/'.$node_id);
		} else {
			die(print_r($table->errors));
		}
	}

}

?>