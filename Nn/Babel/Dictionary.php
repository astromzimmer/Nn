<?php

namespace Nn\Babel;

class Dictionary {

	protected $translations;

	public function __construct($lang=null) {
		$this->setLanguage($lang);
	}

	public function setLanguage($lang) {
		if(isset($lang)) {
			$path = __DIR__.DS.$lang.'.php';
			$this->translations = (file_exists($path)) ? include($path) : [];
		}
	}
	
	public function translate($phrase) {
		$lc_phrase = strtolower($phrase);
		return (isset($this->translations[$lc_phrase])) ? $this->translations[$lc_phrase] : $phrase;
	}
}