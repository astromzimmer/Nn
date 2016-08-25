<?php

namespace Nn\Modules\Document;
use Nn;
use Utils;

class Document extends Nn\Modules\Attachment\Attachment {

	public function exportProperties() {
		return array(
			'id'			=>	$this->id,
			'title'			=>	$this->title,
			'description'	=>	$this->description,
			'path'			=>	$this->publicDir(),
			'filename'		=>	$this->filename,
			'created_at'	=>	$this->created_at,
			'updated_at'	=>	$this->updated_at
		);
	}

	public function make($title, $description, $file){
		if($this->attach_file($file)){
			$this->title = $title;
			$this->description = $description;
			return $this;
		} else {
			return false;
		}
	}
	
}
