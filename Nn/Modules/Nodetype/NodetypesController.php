<?php

namespace Nn\Modules\Nodetype;
use Nn\Modules\Node\Node as Node;
use Nn\Modules\Nodetype\Nodetype as Nodetype;
use Nn\Modules\Attributetype\Attributetype as Attributetype;
use Nn\Modules\Layout\Layout as Layout;
use Nn;
use Utils;

class NodetypesController extends Nn\Core\Controller {

	protected $icons;

	function before() {
		Nn::authenticate();

		$this->icons = [
			'fa-user'=> '&#xf007;',
			'fa-child'=> '&#xf1ae;',
			'fa-picture-o'=> '&#xf03e;',
			'fa-asterisk'=> '&#xf069;',
			'fa-star-o'=> '&#xf006;',
			'fa-star'=> '&#xf005;',
			'fa-folder-open'=> '&#xf07c;',
			'fa-file-text'=> '&#xf15c;',
			'fa-location-arrow'=> '&#xf124;',
		];
	}
	
	function index() {
		$this->setTemplateVars([
				'nodetypes'=> Nodetype::find_all(null,'position')
			]);
	}
	
	function view($id=null) {
		$this->setTemplateVars([
				'nodetypes'=> Nodetype::find_all(null,'position')
			]);
		$this->setTemplateVars([
				'nodetype'=> Nodetype::find($id)
			]);
	}
	
	function sort() {
		$this->renderMode('raw');
		$nodetypes = $_POST['nodetypes'];
		for($i = 0; $i < count($nodetypes); $i++) {
			$nodetype = Nodetype::find($nodetypes[$i]);
			$nodetype->attr('position',$i);
			if(!$nodetype->save()) {
				Utils::sendResponseCode(500);
			}
		}
		Utils::sendResponseCode(200);
	}
	
	function make() {
		$this->setTemplateVars([
				'nodetypes'=> Nodetype::find_all(null,'position'),
				'attributetypes'=> Attributetype::find_all(null,'position'),
				'layouts'=> Layout::find_all(null,'position'),
				'icons'=> $this->icons
			]);
	}
	
	function create() {
		$name = isset($_POST['name']) ? $_POST['name'] : null;
		$icon = isset($_POST['icon']) && $_POST['icon'] != 'null' ? $_POST['icon'] : false;
		$attributetypes = isset($_POST['attributetypes']) ? $_POST['attributetypes'] : null;
		$nodetypes = isset($_POST['nodetypes']) ? $_POST['nodetypes'] : null;
		$layout_id = isset($_POST['layout_id']) ? $_POST['layout_id'] : null;
		$can_be_root = isset($_POST['can_be_root']) ? $_POST['can_be_root'] : 0;
		$nodetype = new Nodetype($name,$icon,$can_be_root,$attributetypes,$nodetypes,$layout_id);
		if($nodetype && $nodetype->save()) {
			Utils::redirect_to(DOMAIN.'/admin/nodetypes');
		} else {
			Nn::flash(['error'=>Nn::babel('Please fill in all fields')]);
			Utils::redirect_to(DOMAIN.'/admin/nodetypes/make');
		}
	}
	
	function edit($id=null) {
		$this->setTemplateVars([
				'nodetypes'=> Nodetype::find_all(null,'position'),
				'attributetypes'=> Attributetype::find_all(null,'position'),
				'layouts'=> Layout::find_all(null,'position'),
				'nodetype'=> Nodetype::find($id),
				'icons'=> $this->icons
			]);
	}
	
	function update($id=null) {
		$name = isset($_POST['name']) ? $_POST['name'] : null;
		$icon = isset($_POST['icon']) && $_POST['icon'] != 'null' ? $_POST['icon'] : false;
		$attributetypes = isset($_POST['attributetypes']) ? $_POST['attributetypes'] : array();
		$nodetypes = isset($_POST['nodetypes']) ? $_POST['nodetypes'] : array();
		$layout_id = isset($_POST['layout_id']) ? $_POST['layout_id'] : null;
		$can_be_root = isset($_POST['can_be_root']) ? $_POST['can_be_root'] : 0;
		$nodetype = Nodetype::find($id);
		$nodetype->attr('name',$name);
		$nodetype->attr('icon',$icon);
		$nodetype->attr('can_be_root',$can_be_root);
		$nodetype->attr('attributetypes',implode(",",$attributetypes));
		$nodetype->attr('nodetypes',implode(",",$nodetypes));
		$nodetype->attr('layout_id',$layout_id);
		if($nodetype->save()) {
			Utils::redirect_to(DOMAIN.'/admin/nodetypes');
		} else {
			Nn::flash(['error'=>Nn::babel("Oups! Error. We'll have a look.")]);
			Utils::redirect_to(DOMAIN.'/admin/nodetypes/edit/'.$nodetype->attr('id'));
		}
	}
	
	function delete($id=null) {
		$nodes = Node::find(['nodetype_id'=>$id]);
		if($nodes) {
			Nn::flash(['error'=>Nn::babel('There are '.count($nodes).' node(s) of this type. Please remove before continuing.')]);
			Utils::redirect_to(DOMAIN.'/admin/nodetypes/edit/'.$id);
		} else {
			$nodetype = Nodetype::find($id);
			if(!$nodetype->delete()) {
				Nn::flash(['error'=>Nn::babel("Oups! Error. We'll have a look.")]);
				Utils::redirect_to(DOMAIN.'/admin/nodetypes/edit/'.$id);
			}
			Utils::redirect_to(DOMAIN.DS.'admin'.DS.'nodetypes');
		}
	}
}

?>