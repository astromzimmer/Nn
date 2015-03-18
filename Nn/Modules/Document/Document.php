<?php

namespace Nn\Modules\Document;
use Nn;
use Utils;
use Nn;

class Document extends Nn\Modules\Datatype\Datatype {

	protected $title;
	protected $description;
	protected $filename;
	protected $type;
	protected $size;
	
	protected $_tmp_path;
	protected $_errors;

	protected $_upload_errors = array(
		UPLOAD_ERR_OK			=> "No errors",
		UPLOAD_ERR_INI_SIZE		=> "File is larger than upload_max_filesize",
		UPLOAD_ERR_FORM_SIZE	=> "File is larger than MAX_FORM_SIZE",
		UPLOAD_ERR_PARTIAL		=> "Only partial upload",
		UPLOAD_ERR_NO_FILE		=> "No file selected",
		UPLOAD_ERR_NO_TMP_DIR	=> "No tmp dir specified",
		UPLOAD_ERR_CANT_WRITE	=> "Can't write to target directory",
//		UPLOAD_ERR_EXTENSION	=> "file upload stopped by extension"
	);

	public static $SCHEMA = array(
			'attribute_id' => 'integer',
			'title' => 'short_text',
			'description' => 'text',
			'filename' => 'short_text',
			'type' => 'short_text',
			'size' => 'float',
			'created_at' => 'integer',
			'updated_at' => 'integer'
		);

	public function exportProperties($excludes=array()) {
		return array(
			'title'			=>	$this->title,
			'filename'		=>	$this->filename,
			'url'			=>	$this->public_path(),
			'type'			=>	$this->type,
			'size'			=>	$this->size,
			'created_at'	=>	$this->created_at,
			'updated_at'	=>	$this->updated_at,
		);
	}

	public function errors() {
		return $this->_errors;
	}

	public function title() {
		$title = ($this->title == "") ? $this->filename : $this->title;
		return $title;
	}

	protected function base() {
		$base = ROOT.DS.'public'.DS.'assets'.DS.Utils::getShortClassName(get_class($this));
		if(!file_exists($base)) {
			mkdir($base);
		}
		return $base;
	}

	protected function dir() {
		$dir = $this->base().DS.$this->id;
		if(!file_exists($dir)) {
			mkdir($dir);
		}
		return $dir;
	}
	
	public function path() {
		return $this->dir().DS.$this->filename;
	}
	
	public function publicDir() {
		return '/assets/'.Utils::getShortClassName(get_class($this)).'/'.$this->id;
	}

	public function publicPath() {
		return $this->publicDir().'/'.$this->filename;
	}

	public function hasFile() {
		if(!file_exists($this->path())) {
			return false;
		}
		return true;
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
	
	public function attach_file($file) {
		// error checking
		if(!$file || empty($file) || !is_array($file)) {
			$this->_errors[] = "no file uploaded";
			return false;
		} elseif($file['error'] > 0) {
			// print_r($this->_upload_errors[$file['error']]);
			$this->_errors[] = $this->_upload_errors[$file['error']];
			return false;
		} else {
			// set object attributes to form params
			$this->_tmp_path = $file['tmp_name'];
			$this->filename = basename(preg_replace("/[^\.a-zA-Z0-9s-]/","_",$file['name']));
			$this->type = $file['type'];
			$this->size = $file['size'];
			return true;
		}
	}
	
	public function save() {
		if(isset($this->id)) {
			return parent::save();
		} else {
			if(!empty($this->_errors)) {
				return false;
			}
			if(empty($this->filename) || empty($this->_tmp_path)) {
				$this->_errors[] = "no file location available";
				return false;
			}
			
			if(parent::save()) {				
				if(file_exists($this->path())) {
					$this->_errors[] = "file already exists";
					return false;
				}
				if(move_uploaded_file($this->_tmp_path, $this->path())) {
					// success!
					unset($this->_tmp_path);
					return true;
				} else {
					// failure... :/
					$this->_errors[] = "the file upload failed miserably...";
					return false;
				}
			} else {
				$this->_errors[] = "can't save to DB";
				return false;
			}
		}
		$this->created_at = time();
	}
	
	public function delete() {
		if(Utils::recursiveRemove($this->dir())) {
			if(parent::delete()) {
				return true;
			}
		}
		return false;
	}
	
}
