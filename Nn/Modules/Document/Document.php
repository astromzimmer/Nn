<?php

namespace Nn\Modules\Document;
use Nn;
use Utils;

class Document extends Nn\Modules\Attachment\Attachment {

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
