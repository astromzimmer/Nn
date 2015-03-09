<?php

namespace Nn\Controllers;
use Nn\Models\Image as Image;
use Nn;
use Utils;

class DefaultController extends Nn\Core\Controller {
	
	function before() {
		// TODO: Make this possible
		// $this->cache_multiple(array('index','mobile'));
	}
	
	function notFound() {
		// echo 'Not Found';
	}

	function thumbnail($id,$filename) {
		$filename_array = explode('-', $filename);
		$bound = (int)$filename_array[0];
		if($bound == 0) {
			Utils::redirect_to(DOMAIN.DS.'404');
		} else {
			$h = ($filename_array[1] == 'h');
			$bw = (isset($filename_array[2]) && ($filename_array[1] == 'bw' || $filename_array[2] == 'bw'));
			$image = Image::find($id);
			if($src = $image->src($bound,$h,$bw)) {
				Utils::redirect_to($src);
			} else {
				Utils::redirect_to('/500');
			}
		}
	}
	
	function after() {
	
	}
}

?>