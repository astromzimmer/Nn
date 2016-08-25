<?php

namespace Nn\Modules\Document;
use Nn\Modules\Attribute\Attribute as Attribute;
use Nn;
use Utils;

class DocumentsController extends Nn\Core\Controller {
	
	function before() {
		Nn::authenticate();
	}
	
	function index() {
		$this->setTemplateVars([
				'documents'=> Document::find_all()
			]);
	}
	
	function view($id=null) {
		$this->setTemplateVars([
				'document'=> Document::find($id)
			]);
	}
	
	function make() {
		
	}
	
	function edit($id=null) {
		if(isset($id)) {
			$this->setTemplateVars([
					'document'=> Document::find($id)
				]);
		} else {
			$this->setTemplateVars([
					'document'=> new Document()
				]);
		}
	}

	function create() {
		$node_id = $_POST['node_id'];
		$files = Utils::fixFilesArray($_FILES['file_upload']);
		usort($files,function($a,$b){
			if($a['name'] == $b['name']){
				return 0;
			}
			return ($a['name'] > $b['name']) ? 1 : -1;
		});
		foreach($files as $file) {
			$document = new Document();
			$title = isset($_POST['title']) ? $_POST['title'] : '';
			$description = isset($_POST['description']) ? $_POST['description'] : '';
			if($document->make($title, $description, $file)) {
				if($document->save()) {
					$attribute = new Attribute($node_id,$_POST['atype_id'],$document->attr('id'));
					if(!$attribute->save()) {
						$document->delete();
						Nn::flash(['error'=>Nn::babel("Failed to register attribute")]);
						Utils::redirect(Nn::referer());
					}
				}
			} else {
				$attribute->delete();
				Nn::flash(['error'=>$document->errors()[0]]);
				Utils::redirect(Nn::referer());
			}
		}
		Utils::redirect(Nn::s('DOMAIN').'/admin/nodes/'.Nn::settings('NODE_VIEW').'/'.$node_id);
	}
	
	function update($id=null) {
		$document = Document::find($id);
		$document->attr('title',$_POST['title']);
		$document->attr('description',$_POST['description']);
		$node_id = $_POST['node_id'];
		if($document->save()) {
			# Attribute save needs error handling - yawn
			$attribute = $document->attribute();
			$attributetype_id = $_POST['attributetype_id'];
			$attribute->attr('attributetype_id',$attributetype_id);
			$attribute->save();
			Utils::redirect(Nn::s('DOMAIN').'/admin/nodes/'.Nn::settings('NODE_VIEW').'/'.$node_id);
		} else {
			die(print_r($document->errors));
		}
	}

}

?>