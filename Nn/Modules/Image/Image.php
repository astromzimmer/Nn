<?php

namespace Nn\Modules\Image;

class Image extends \Nn\Modules\Attachment\Attachment {
	
	protected $href;
	
	public static $SCHEMA = array(
			'title' => 'short_text',
			'description' => 'text',
			'filename' => 'short_text',
			'href' => 'text',
			'type' => 'short_text',
			'size' => 'float',
			'created_at' => 'float',
			'updated_at' => 'float'
		);

	public function exportProperties() {
		return array(
			'id'			=>	$this->id,
			'title'			=>	$this->title,
			'description'	=>	$this->description,
			'filename'		=>	$this->filename,
			'sizes'			=>	[
				'thumb'			=>	$this->src(172),
				'thumb2x'		=>	$this->src(344),
				'medium'		=>	$this->src(720),
				'medium2x'		=>	$this->src(1440),
				'original'		=>	$this->src(),
			],
			'type'			=>	$this->type,
			'size'			=>	$this->size(),
			'created_at'	=>	$this->created_at,
			'updated_at'	=>	$this->updated_at,
		);
	}

	public function make($title, $description, $href, $file){
		if($this->attach_file($file)){
			$this->title = $title;
			$this->description = $description;
			$this->href = $href;
			return $this;
		} else {
			return false;
		}
	}
	
	public function size($path=null) {
		if(!isset($this->_size)) {
			$path = isset($path) ? $path : $this->path();
			$this->_size = getimagesize($path);
		}
		return $this->_size;
	}
	
	public function hasHref() {
		return ($this->href && $this->href != '');
	}

	public function src($bound=null,$is_height=false,$is_bw=false,$is_alpha=false,$cmpr=null) {
		$filename = $this->filename;
		if($this->type != 'image/gif') {
			if($is_alpha) $filename = 'a-'.$filename;
			if($is_bw) $filename = 'bw-'.$filename;
			if($is_height) $filename = 'h-'.$filename;
			if(isset($bound)) $filename = $bound.'-'.$filename;
		}
		$path = ROOT.DS.'public'.DS.'assets'.DS.'Image'.DS.$this->id.DS.$filename;
		if(!file_exists($path)) {
			if(!$this->generate($bound,$is_height,$is_bw,$is_alpha)) return false;
		}
		return $this->publicDir().'/'.$filename;
	}

	public function tag($bound=null,$is_height=false,$is_bw=false,$is_alpha=false,$cmpr=null) {
		$src = $this->src($bound,$is_height,$is_bw,$is_alpha,$cmpr);
		$src_attr = 'src="'.$src.'" ';
		$path = str_replace($this->publicDir(), $this->dir(), $src);
		$size = $this->size($path);
		if(isset($bound)) {
			 $retina_src = $this->src($bound*2,$is_height,$is_bw,$is_alpha,$cmpr);
			 $src_attr .= 'srcset="'.$src.' 1x,'.$retina_src.' 2x" ';
		}
		return '<img '.$src_attr.'alt="'.$this->title.'" width="'.$size[0].'" height="'.$size[1].'">';
	}
	
	public function generate($bound,$is_height,$is_bw,$is_alpha,$comp=null) {
		$path = $this->path();
		if(!file_exists($path)) {
			# Error handling
			return false;
		}

		switch($this->type) {
			case "image/jpg":
			case "image/jpeg":
			case "image/pjpeg":
				$this->_img = imagecreatefromjpeg($path);
				break;
			case "image/png":
				$this->_img = imagecreatefrompng($path);
				imagealphablending($this->_img, true);
				break;
			case "image/gif":
				$this->_img = imagecreatefromgif($path);
				break;
		}
		
		if($this->writeImg($bound,$is_height,$is_bw,$is_alpha,$comp)) {
			// success!
			return true;
		} else {
			// failure... :/
			$this->_errors[] = "the image upload failed miserably...";
			return false;
		}
	}
	
	private function getBounds($bound,$is_height) {
		if(in_array($this->type, ['image/jpg','image/jpeg','image/pjpeg'])) $this->rotate();
		$width = imagesx($this->_img);
		$height = imagesy($this->_img);
		if(isset($bound)) {
			if($is_height) {
				$h = $bound;
				$w = $width * ($h/$height);
			} else {
				$w = $bound;
				$h = $height * ($w/$width);
			}
		} else {
			$w = $width;
			$h = $height;
		}
		
		return array(
				'original_width' => $width,
				'scaled_width'=> $w,
				'original_height' => $height,
				'scaled_height' => $h
			);
	}

	private function rotate() {
		# Here we adjust orientation
		try {
			$path = $this->path();
			if(($exif = exif_read_data($path)) && isset($exif['Orientation'])){
				$deg = 0;
				switch($exif['Orientation']) {
					case 3:
						# Rotate 180deg
						$deg = 180;
					case 6:
						# Rotate 90deg clockwise
						$deg = 90;
					case 8:
						# Rotate 90deg counter clockwise
						$deg = -90;
				}
				$this->_img = imagerotate($this->_img,$deg,0);
			}
		} catch(\Exception $e) {
			// nah
		}
	}
	
	private function writeImg($bound,$is_height,$is_bw,$is_alpha=false,$comp=null) {
		if(empty($this->_img)) return false;
		$bounds = $this->getBounds($bound,$is_height);
		
		// Set sth up to apply for PNG
		$compression = isset($comp) ? $comp : 72;

		$new_img = imagecreatetruecolor($bounds['scaled_width'], $bounds['scaled_height']);
		imagealphablending($new_img, false);
		imagesavealpha($new_img, true);
		imagecopyresampled($new_img, $this->_img, 0, 0, 0, 0, $bounds['scaled_width'], $bounds['scaled_height'], $bounds['original_width'], $bounds['original_height']);
		$filename = $this->filename;
		if($is_bw) {
			imagefilter($new_img,IMG_FILTER_GRAYSCALE);
		}
		if($is_alpha && $this->type == 'image/png') {
			$mask = imagecreatetruecolor($bounds['scaled_width'], $bounds['scaled_height']);
			imagecopy($mask, $new_img, 0, 0, 0, 0, $bounds['scaled_width'], $bounds['scaled_height']);
			$new_img = self::alpha($new_img);
		}
		# Should really clean this up...
		if($is_alpha) $filename = 'a-'.$filename;
		if($is_bw) $filename = 'bw-'.$filename;
		if(isset($bound)) {
			if($is_height) {
				$filename = $bounds['scaled_height'].'-h-'.$filename;
			} else {
				$filename = $bounds['scaled_width'].'-'.$filename;
			}
		}
		$target_path = $this->dir().DS.$filename;
		
		switch($this->type) {
			case "image/jpg":
			case "image/jpeg":
			case "image/pjpeg":
				imageinterlace($new_img,true);
				imagejpeg($new_img, $target_path, $compression);
				break;
			case "image/png":
				imagepng($new_img, $target_path, 3);
				break;
			case "image/gif":
				imagegif($new_img, $target_path);
				break;
		}
		imagedestroy($this->_img);
		imagedestroy($new_img);
		return true;
	}

	private static function alpha($img) {
		$width = imagesx($img);
		$height = imagesy($img);
		$new_img = imagecreatetruecolor($width, $height);
		$transparent = imagecolorallocatealpha($new_img, 0, 0, 0, 127);
		imagefill($new_img, 0, 0, $transparent);
		for($x=0; $x < $width; $x++) { 
			for($y=0; $y < $height; $y++) {
				$colour = imagecolorsforindex($img, imagecolorat($img, $x, $y));
				$alpha = (1-($colour['red']/255))*127;
				imagesetpixel($new_img, $x, $y, imagecolorallocatealpha($new_img, 255, 255, 255, $alpha));
			}
		}
		imagealphablending($new_img, true);
		imagesavealpha($new_img, true);
		imagedestroy($img);
		return $new_img;
	}

	public static function placeholder($w,$h,$bg_clrs,$fnt_clrs) {
		$width = isset($w) ? $width : 200;
		$height = isset($h) ? $height : 200;
		$bg_clrs = isset($bg_clrs) ? $bg_clrs : 200;
		$fnt_clrs = isset($fnt_clrs) ? $fnt_clrs : 200;
		$size = 12;
		$angle = 0;
		$font = 'Arial';
		$x = 0;
		$y = $size;
		$img = imagecreatetruecolor($width,$height);
		$background_colour = imagecolorallocate($img,$bg_clrs[0],$bg_clrs[1],$bg_clrs[2]);
		$font_colour = imagecolorallocate($img,$fnt_clrs[0],$fnt_clrs[1],$fnt_clrs[2]);
		imagettftext($img,$size,$angle,$x,$y,$txtColour,$font,$str);
		imagefill($img,0,0,$bgColour);
		imagealphablending($img,false);
		imagesavealpha($img,true);
		imagepng($img, $str.".png");
		imagedestroy($img);
	}
	
	public function delete() {
		if(parent::delete()) {
			return true;
		}
		return false;
	}
		
}

?>