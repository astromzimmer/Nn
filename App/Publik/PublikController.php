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

	function index($root=null,$section=null) {
		$assets = Node::find(['title'=>'Assets'],1);
		$logo = $assets->attribute('Logo');
		if(Utils::is_mobile()){
			Utils::redirect_to('/mobile');
		} else {
			$art = Node::find(['title'=>'ART'],1);
			$app = Node::find(['title'=>'APP'],1);
			$root = (isset($root)) ? $root : '';
			$this->setTemplateVars([
				'logo' => $logo,
				'art' => $art,
				'app' => $app,
				'root'=> $root,
				'section'=> $section
			]);
		}
	}

	function mobile() {
		
	}
	
	function after() {
	
	}
}

?>