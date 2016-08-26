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
		Utils::sendResponseCode(404,false);
	}

	function error() {
		Utils::sendResponseCode(500,false);
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
			Utils::redirect($src);
		} else {
			Utils::redirect('/404');
		}
	}

	function placeholder($width,$height,$bg_colour,$fnt_colour) {
		$image = imagecreatetruecolor($width, $height);
		if(isset($bg_colour)) {
			$background_colour = Utils::getImageColorFromHex($image,$bg_colour);
		} else {
			$background_colour = imagecolorallocate($image, 0xAA, 0xCC, 0xDD);
		}
		if(isset($fnt_colour)) {
			$font_colour = Utils::getImageColorFromHex($image,$fnt_colour);
		} else {
			$font_colour = imagecolorallocate($image, 0xAA, 0xCC, 0xDD);
		}
		imagefilledrectangle($image, 0, 0, $width, $height, $background_colour);
		$font = 3;
		$string = "{$width}x{$height}px";
		$text_width = imagefontwidth($font) * strlen($string);
		$x = ($width - $text_width) / 2;
		imagestring($image, $font, $x, $height/2, $string, $font_colour);
		$this->renderMode('image','image/gif');
		$this->setTemplateVars([
				'data'=>$image
			]);
	}

	function halftone($id) {
		$image = Image::find($id);
		$img = imagecreatefromjpeg($image->path());
		imagefilter($img,IMG_FILTER_COLORIZE,255,0,0,0);
		imagetruecolortopalette($img, TRUE, 2);
		imagefilter($img,IMG_FILTER_PIXELATE,3);
		$this->renderMode('image','image/jpg');
		$this->setTemplateVars([
				'data'=>$img
			]);
	}

	function privacy() {
		
	}
	
	function after() {
	
	}
}

?>