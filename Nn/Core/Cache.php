<?php

	namespace Nn\Core;
	use Nn;
	
	class Cache {
	
		private $file_path;
		
		public function flush($prefix='') {
			self::unlinkWithPrefix($prefix);
			self::unlinkWithPrefix('RENDER');
			# Error checking?
			return true;
		}

		private static function unlinkWithPrefix($prefix) {
			$mask = Nn::settings('CACHE_DIR').DS.$prefix.'*';
			foreach(glob($mask) as $path) {
				unlink($path);
			}
		}
		
		public function __construct() {
			//
		}

		public function filePath($id) {
			return Nn::settings('CACHE_DIR').DS.$id.'.cache';
		}
		
		public function set($id,$data) {
			$file_path = $this->filePath($id);
			if(!Nn::settings('DEVELOPMENT_ENV')){
				if(file_exists($file_path)) {
					unlink($file_path);
				}
				if(!file_put_contents($file_path,serialize($data))) {
					throw new \Exception('Unable to write data to cache');
				}
			}
		}
		
		public function get($id) {
			$file_path = $this->filePath($id);
			if(!$data = file_get_contents($file_path)) {
				throw new \Exception('Unable to read cached data');
			}
			return unserialize($data);
		}
		
		public function valid($id) {
			if(Nn::settings('DEVELOPMENT_ENV')){
				$valid = false;
			} else {
				$file_path = $this->filePath($id);
				$valid = file_exists($file_path);
				if(Nn::settings('CACHE_EXPIRE')) {
					$valid = (bool)(time() - filemtime($file_path) <= Nn::settings('CACHE_EXPIRE'));
				}
			}
			return $valid;
		}
	}

?>