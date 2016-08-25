<?php

	namespace Nn\Core;
	use Nn;
	
	class Cache {
	
		private $file_path;
		
		public function flush($prefix='') {
			self::unlinkWithPrefix($prefix);
			if($prefix != 'visitors') self::unlinkWithPrefix('RENDER');
			# Error checking?
			return true;
		}

		private static function unlinkWithPrefix($prefix) {
			$mask = Nn::settings('DIRS')['CACHE'].DS.$prefix.'*';
			clearstatcache();
			foreach(glob($mask) as $path) {
				if(is_file($path)) {
					unlink($path);
				}
			}
		}
		
		public function __construct() {
			//
		}

		public function filePath($id) {
			if(!is_dir(Nn::settings('DIRS')['CACHE'])) {
				mkdir(Nn::settings('DIRS')['CACHE'],0755,true);
			}
			return Nn::settings('DIRS')['CACHE'].DS.$id.'.cache';
		}
		
		public function set($id,$data) {
			$file_path = $this->filePath($id);
			if(!Nn::settings('DEVELOPMENT_ENV')){
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
				$valid = is_file($file_path);
				if(Nn::settings('CACHE_EXPIRE')) {
					$valid = (bool)(time() - filemtime($file_path) <= Nn::settings('CACHE_EXPIRE'));
				}
			}
			return $valid;
		}
	}

?>