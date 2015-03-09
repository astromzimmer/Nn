<?php

namespace Nn\Models;

class Image extends Document {
	
	protected $_size;
	
	public function exportProperties($excludes=array()) {
		return array(
			'id'			=>	$this->id,
			'title'			=>	$this->title,
			'description'	=>	$this->description,
			'filename'		=>	$this->filename,
			'url'			=>	$this->src(),
			'type'			=>	$this->type,
			'size'			=>	$this->size,
			'created_at'	=>	$this->created_at,
			'updated_at'	=>	$this->updated_at,
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
	
	public function size($path=null) {
		if(!isset($this->_size)) {
			$path = isset($path) ? $path : $this->path();
			$this->_size = getimagesize($path);
		}
		return $this->_size;
	}
	
	public function src($bound=null,$is_height=false,$is_bw=false) {
		$filename = $this->filename;
		if($is_bw) $filename = 'bw-'.$filename;
		if($is_height) $filename = 'h-'.$filename;
		if(isset($bound)) $filename = $bound.'-'.$filename;
		$path = ROOT.DS.'public'.DS.'assets'.DS.'Image'.DS.$this->id.DS.$filename;
		if(!file_exists($path)) {
			if(!$this->generate($bound,$is_height,$is_bw)) return false;
		}
		return $this->publicDir().'/'.$filename;
	}

	public function tag($bound=null,$is_height=false,$is_bw=false) {
		$src = $this->src($bound,$is_height,$is_bw);
		$src_attr = 'src="'.$src.'" ';
		$path = str_replace($this->publicDir(), $this->dir(), $src);
		$size = $this->size($path);
		if(isset($bound)) {
			 $retina_src = $this->src($bound*2,$is_height,$is_bw);
			 $src_attr .= 'data-retina_src="'.$retina_src.'" ';
		}
		return '<img '.$src_attr.'alt="'.$this->title.'" width="'.$size[0].'" height="'.$size[1].'">';
	}
	
	public function generate($bound,$is_height,$is_bw) {
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
		
		$bounds = $this->getBounds($bound,$is_height);
		if($this->writeImg($bounds,$is_height,$is_bw)) {
			// success!
			return true;
		} else {
			// failure... :/
			$this->_errors[] = "the image upload failed miserably...";
			return false;
		}
	}
	
	private function getBounds($bound,$is_height) {
		if(!empty($this->_img)){
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
	}
	
	private function writeImg($bounds,$is_height,$is_bw) {
		$new_img = imagecreatetruecolor($bounds['scaled_width'], $bounds['scaled_height']);
		imagealphablending($new_img, false);
		imagesavealpha($new_img, true);
		imagecopyresampled($new_img, $this->_img, 0, 0, 0, 0, $bounds['scaled_width'], $bounds['scaled_height'], $bounds['original_width'], $bounds['original_height']);
		$filename = $this->filename;
		if($is_bw) {
			imagefilter($new_img,IMG_FILTER_GRAYSCALE);
			$filename = 'bw-'.$filename;
		}
		if($is_height) {
			$filename = $bounds['scaled_height'].'-h-'.$filename;
		} else {
			$filename = $bounds['scaled_width'].'-'.$filename;
		}
		$target_path = $this->dir().DS.$filename;
		
		switch($this->type) {
			case "image/jpg":
			case "image/jpeg":
			case "image/pjpeg":
				imagejpeg($new_img, $target_path, 100);
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
	
	public function delete() {
		if(parent::delete()) {
			return true;
		}
		return false;
	}
		
}

?>