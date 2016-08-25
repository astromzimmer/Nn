<?php

namespace Nn\Modules\Form;
use Nn;
use Utils;
use \HTMLPurifier;
use \HTMLPurifier_Config;

class Entry extends Nn\Core\DataModel {

	protected $form_id;
	protected $data;
	
	public static $SCHEMA = array(
			'form_id' => 'integer',
			'data' => 'long_text',
			'files' => 'text',
			'created_at' => 'double',
			'updated_at' => 'double'
		);
	
	public function __construct($form_id=null){
		if(!empty($form_id)){
			$this->form_id = $form_id;
			return $this;
		} else {
			return false;
		}
	}

	static function uploadDir() {
		return ROOT.DS.'form_data'.DS.'uploads';
	}

	private function dir() {
		return self::uploadDir().DS.$this->id;
	}
	
	public function data($data=null){
		if(isset($data)){
			if($data['spmchk'] == 'hmn') {
				unset($data['spmchk']);
				unset($data['MAX_FILE_SIZE']);
				unset($data['submit']);
				$htmlPurifierConfig = HTMLPurifier_Config::createDefault();
				$htmlPurifier = new HTMLPurifier($htmlPurifierConfig);
				$clean_data = [];
				foreach($data as $field=>$value) {
					$clean_value = $htmlPurifier->purify($value);
					$clean_data[$field] = $clean_value;
				}
				$this->data = serialize($clean_data);
				return true;
			} else {
				$this->errors('Spam!');
				return false;
			}
		} else {
			return unserialize($this->data);
		}
	}

	public function attachmentPath($file=null) {
		if(isset($file)) {
			return DOMAIN.'/admin/forms/attachment/'.$this->id.'/'.$file['name'];
		}
	}

	public function files($files=null){
		if(isset($files)){
			$parsed_files = [];
			foreach($files as $name=>$value) {
				if($value['size'] > 0) {
					$tmp_name = $value['tmp_name'];
					$dir = $this->dir();
					if(!file_exists($dir)) {
						mkdir($dir,0777,true);
					}
					$path = $dir.DS.$value['name'];
					if(move_uploaded_file($tmp_name, $path)) {
						# Tiptop
						unset($value['tmp_name']);
						$value['path'] = $path;
					} else {
						array_push($this->_errors,'Unable to move uploaded file.');
						return false;
					}
					$parsed_files[$name] = $value;
				} else {
					array_push($this->_errors,'No file.');
				}
			}
			$this->files = serialize($parsed_files);
			return true;
		} else {
			return unserialize($this->files);
		}
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

?>