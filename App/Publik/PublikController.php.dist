<?php

namespace App\Publik;
use Nn\Modules\Node\Node as Node;
use Nn\Modules\Attribute\Attribute as Attribute;
use Nn\Modules\Image\Image as Image;
use Nn;
use Utils;

class PublikController extends Nn\Core\Controller {
	
	function before() {
		// TODO: Make this possible
		$this->cache(['index','mobile']);
		Nn::settings('HIDE_INVISIBLE',1);
	}

	function index($section=null) {
		if(Utils::is_mobile()){
			Utils::redirect_to('/mobile');
		} else {
			$this->setTemplateVars([
				'target'=> 'World'
			]);
		}
	}

	function mobile() {
		
	}
	
	function after() {
	
	}
}

?>