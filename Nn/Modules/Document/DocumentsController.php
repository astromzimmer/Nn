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
		$document = new Document();
		$document->attr('attribute_id',$attribute->attr('id'));
		if($document->make($_POST['title'], $_POST['description'], $_FILES['file_upload'])) {
			if($document->save()) {
				$attribute = new Attribute($node_id,$_POST['atype_id'],$document->attr('id'));
				if($attribute->save()) {
					Utils::redirect_to(DOMAIN.DS.'admin'.DS.'nodes'.DS.'view'.DS.$node_id);
				} else {
					$document->delete();
					Nn::flash(['error'=>Nn::babel('Failed to register attribute')]);
					Utils::redirect_to(Nn::referer());
				}
			}
		} else {
			Nn::flash(print_r($document->errors()));
			Utils::redirect_to(Nn::referer());
		}
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
			Utils::redirect_to(DOMAIN.DS.'admin'.DS.'nodes'.DS.'view'.DS.$node_id);
		} else {
			die(print_r($document->errors));
		}
	}

}

?>