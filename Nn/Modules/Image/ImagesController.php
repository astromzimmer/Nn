<?php

namespace Nn\Modules\Image;
use Nn\Modules\Attribute\Attribute as Attribute;
use Nn;
use Utils;

class ImagesController extends Nn\Core\Controller {
	
	function before() {
		Nn::authenticate();
	}
	
	function index() {
		$this->setTemplateVars([
				'images'=> Image::find_all()
			]);
	}
	
	function view($id=null) {
		$this->setTemplateVars([
				'image'=> Image::find($id)
			]);
	}
	
	function make() {
		
	}
	
	function edit($id=null) {
		if(isset($id)) {
			$this->setTemplateVars([
					'image'=> Image::find($id)
				]);
		} else {
			$this->setTemplateVars([
					'image'=> $this->image
				]);
		}
	}
	
	function create() {
		$node_id = $_POST['node_id'];
		$image = new Image();
		if($image->make($_POST['title'], $_POST['description'], $_POST['href'], $_FILES['file_upload'])) {
			if($image->save()) {
				$attribute = new Attribute($node_id,$_POST['atype_id'],$image->attr('id'));
				if($attribute->save()) {
					Utils::redirect_to(DOMAIN.DS.'admin'.DS.'nodes'.DS.'view'.DS.$node_id);
				} else {
					$image->delete();
					Nn::flash(['error'=>Nn::babel("Failed to register attribute")]);
					Utils::redirect_to(Nn::referer());
				}
			}
		} else {
			$attribute->delete();
			Nn::flash(['error'=>$image->errors()[0]]);
			Utils::redirect_to(Nn::referer());
		}
	}
	
	function update($id=null) {
		$image = Image::find($id);
		$image->attr('title',$_POST['title']);
		$image->attr('description',$_POST['description']);
		$image->attr('href',$_POST['href']);
		$node_id = $_POST['node_id'];
		if($image->save()) {
			# Attribute save needs error handling - yawn
			$attribute = $image->attribute();
			$attributetype_id = $_POST['attributetype_id'];
			$attribute->attr('attributetype_id',$attributetype_id);
			$attribute->save();
			Utils::redirect_to(DOMAIN.DS.'admin'.DS.'nodes'.DS.'view'.DS.$node_id);
		} else {
			die(print_r($image->errors()));
		}
	}

}

?>