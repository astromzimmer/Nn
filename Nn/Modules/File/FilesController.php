<?php

namespace Nn\Modules\File;
use Nn\Modules\Node\Node as Node;
use Nn;

class FilesController extends Nn\Core\Controller {
	
	function before() {
		Nn::authenticate();
	}

	function index() {
		$files = File::getAllInDir();
		$this->setTemplateVars([
				'files'=> $files
			]);
	}

	function view($id=null) {
		$file = File::get($id);
		$files = File::getAllInDir();
		$this->setTemplateVars([
				'file'=> $file
			]);
		$this->setTemplateVars([
				'files'=> $files
			]);
	}

	function edit($id=null) {
		$file = File::get($id);
		$files = File::getAllInDir();
		$this->setTemplateVars([
				'file'=> $file
			]);
		$this->setTemplateVars([
				'files'=> $files
			]);
	}
	
	function create() {
		$file = new File($_POST['path'],$_POST['type'],$_POST['content']);
		if($file->save()) {
			redirect_to(Nn::s('DOMAIN').'/admin/files');
		} else {
			die("failed to create file");
		}
	}
	
	function update($id=null) {
		$file = File::get($id);
		$file = $file->attr('path',$_POST['path']);
		$file = $file->attr('type',$_POST['type']);
		$file = $file->attr('content',$_POST['content']);
		if($file->save()) {
			redirect_to(Nn::s('DOMAIN').'/admin/files');
		} else {
			die("failed to update file");
		}
	}
	
	function delete($id=null) {
		$file = File::get($id);
		if($file->delete()) {
			redirect_to(Nn::s('DOMAIN').'/admin/files');
		}
	}
}

?>