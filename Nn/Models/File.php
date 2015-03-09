<?php

namespace Nn\Models;
use Nn;

class File extends Nn\Core\Model {
	
	protected $id;
	protected $path;
	protected $name;
	protected $extension;
	protected $content;

	public function exportProperties($excludes=array()) {
		return array(
			'path'		=>	$this->path,
			'name'		=>	$this->name,
			'extension'	=>	$this->extension(),
			'content'	=>	$this->content,
		);
	}

	public static function getAllInDir($d=null) {
		$dir = (isset($d)) ? $d : ROOT.DS.'App';
		$root = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir),\RecursiveIteratorIterator::CHILD_FIRST);
		$results = array();
		foreach($root as $splFileInfo) {
			$file_name = $splFileInfo->getFilename();
			if(substr($file_name,0,1) != '.') {
				if($splFileInfo->isDir()) {
					$path = array($splFileInfo->getFilename() => array());
				} elseif($splFileInfo->isFile()) {
					$path = array(new File($splFileInfo->getRealPath()));
				}
				for($depth = $root->getDepth() - 1; $depth >= 0; $depth--) {
					$path = array($root->getSubIterator($depth)->current()->getFilename() => $path);
				}
				$results = array_merge_recursive($results, $path);
			}
		}
		return $results;
	}

	public static function get($hash) {
		$path = base64_decode($hash);
		return new File($path);
	}

	public function __construct($path) {
		if(isset($path)) {
			$pathinfo = pathinfo($path);
			$this->id = base64_encode($path);
			$this->path = $path;
			$this->name = $pathinfo['filename'];
			$this->extension = $pathinfo['extension'];
		}
	}

	public function path() {
		return str_replace(ROOT, '', $this->path);
	}

	public function basename() {
		return $this->name.'.'.$this->extension;
	}

	public function updated_at() {
		return filemtime($this->path);
	}

	public function content() {
		if(isset($this->content)) {
			return $this->content;
		} else {
			if(file_exists($this->path)) {
				$this->content = file_get_contents($this->path);
				return $this->content;
			} else {
				return false;
			}
		}
	}

	public function escaped_content() {
		return htmlentities($this->content());
	}

	public function extension() {
		if(!isset($this->extension)) {
			$this->extension = pathinfo($this->name);
		}
		return $this->extension;
	}

	public function save() {
		if(!file_put_contents($this->path(), $this->content)) return false;
		return true;
	}

	public function delete() {
		if(!unlink($this->path())) return false;
		return true;
	}

}

?>