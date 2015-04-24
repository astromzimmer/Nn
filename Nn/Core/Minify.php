<?php

namespace Nn\Core;
use Nn;

class Minify {
	
	public function jsTags($files=array(),$output='concat.js') {
		if(!Nn::settings('DEVELOPMENT_ENV')) {
			$last_modified = $this->getLastModified($files);
			$output_path = ROOT.DS.'public'.DS.$output;
			$concat_modified = (file_exists($output_path)) ? filemtime($output_path) : 0;
			if($last_modified > $concat_modified) $this->buildJS($files,$output);
			// $this->buildJS($files,$output);
			return '<script src="/'.$output.'?'.$last_modified.'"></script>';
		} else {
			$tag = '';
			foreach ($files as $file) {
				$modified = $this->getLastModified($file);
				$tag .= '<script src="/'.$file.'?'.$modified.'"></script>';
			}
			return $tag;
		}
	}

	public function cssTags($files=array(),$output='concat.css',$media='all') {
		if(!Nn::settings('DEVELOPMENT_ENV')) {
			$last_modified = $this->getLastModified($files);
			$output_path = ROOT.DS.'public'.DS.$output;
			$concat_modified = (file_exists($output_path)) ? filemtime($output_path) : 0;
			if($last_modified > $concat_modified) $this->buildCSS($files,$output);
			// $this->buildCSS($files,$output);
			return '<link href="/'.$output.'?'.$last_modified.'" rel="stylesheet" type="text/css" media="'.$media.'">';
		} else {
			$tag = '';
			foreach ($files as $file) {
				$modified = $this->getLastModified($file);
				$tag .= '<link href="/'.$file.'?'.$modified.'" rel="stylesheet" type="text/css" media="'.$media.'">';
			}
			return $tag;
		}
	}

	private function getLastModified($files) {
		$last_modified = 0;
		if(!is_array($files)) $files = [$files];
		foreach($files as $file) {
			$file_path = ROOT.DS.'public'.DS.$file;
			if(file_exists($file_path)) {
				$age = filemtime($file_path);
				if($age > $last_modified) {
					$last_modified = $age;
				}
			}
		}
		return $last_modified;
	}
	
	private function compress($data) {
		$params_array = [
			'compilation_level'	=> 'ADVANCED_OPTIMIZATIONS',
			'output_format'		=> 'json',
			'output_info'		=> 'compiled_code'
		];
		$params = 'js_code='.urlencode($data);
		foreach($params_array as $key => $val) {
			$params .= '&'.$key.'='.urlencode($val);
		}
		$closure_request = curl_init();
		curl_setopt_array($closure_request, [
			CURLOPT_URL				=> 'http://closure-compiler.appspot.com/compile',
			CURLOPT_POST			=> true,
			CURLOPT_POSTFIELDS		=> $params,
			CURLOPT_RETURNTRANSFER	=> true,
			CURLOPT_HEADER			=> false,
			CURLOPT_FOLLOWLOCATION	=> false
		]);
		$json = curl_exec($closure_request);
		curl_close($closure_request);
		$compiled_data = json_decode($json);
		return $compiled_data;
	}
	
	private function buildJS($files,$output=null) {
		$concat_data = '';
		foreach($files as $file) {
			$file_path = ROOT.DS.'public'.DS.$file;
			$min_file_path = str_replace('.js', '.min.js', $file_path);
			if(file_exists($min_file_path)) {
				$concat_data .= file_get_contents($min_file_path);
			} elseif(file_exists($file_path)) {
				$concat_data .= file_get_contents($file_path);
			}
		}
		// $concat_data = $this->compress($concat_data);
		file_put_contents(ROOT.DS.'public'.DS.$output,$concat_data);
	}

	private function buildCSS($files,$output=null) {
		$concat_data = '';
		foreach($files as $file) {
			$file_path = ROOT.DS.'public'.DS.$file;
			if(file_exists($file_path)) {
				$content = file_get_contents($file_path);
				$minified_content = preg_replace('/\r|\n/', '', $content);
				$concat_data .= $minified_content;
			}
		}
		// $compiled_data = $this->compress($concat_data);
		file_put_contents(ROOT.DS.'public'.DS.$output,$concat_data);
	}

}