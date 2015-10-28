<?php

namespace Nn\Modules\Def;
use Nn\Modules\Image\Image as Image;
use Nn;
use Utils;

class DefController extends Nn\Core\Controller {
	
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
		if($bound == 0) $bound = null;
		# Come on...
		$h = (strpos($filename, 'h-') !== false) ? true : false;
		$bw = (strpos($filename, 'bw-') !== false) ? true : false;
		$alpha = (strpos($filename, 'a-') !== false) ? true : false;
		$image = Image::find($id);
		if($src = $image->src($bound,$h,$bw,$alpha)) {
			Utils::redirect_to($src);
		} else {
			Utils::redirect_to('/500');
		}
	}
	
	function after() {
	
	}
}

?>