<?php

namespace Nn\Modules\Node;
use Nn\Modules\Node\Node as Node;
use Nn\Modules\Attributetype\Attributetype as Attributetype;
use Nn\Modules\Nodetype\Nodetype as Nodetype;
use Nn\Modules\Publication\Publication as Publication;
use Nn\Modules\Publication\Section as Section;
use Nn;
use Utils;

class NodesController extends Nn\Core\Controller {
	
	function before() {
		Nn::authenticate();
	}
	
	function index() {
		$nodes = Node::find(array('parent_id'=>0),null,'position');
		$this->setTemplateVars([
				'node'=> false
			]);
		$this->setTemplateVars([
				'nodes'=> $nodes
			]);
	}

	function tree($node=null) {
		$nodes = Node::find(array('parent_id'=>0),null,'position');
		if(!is_object($node)) {
			$node = (isset($node) && $node != 0) ? Node::find($node) : new Node();
		}
		$this->setTemplateVars([
				'node' => $node,
				'nodes' => $nodes
			]);
	}
	
	function view($id=null,$atype_id=null,$attr_id=null) {
		$nodes = Node::find(array('parent_id'=>0),null,'position');
		$dtype = null;
		$atype = null;
		if(isset($atype_id) && is_int($atype_id)) {
			$atype = Attributetype::find($atype_id);
			$dtype = $atype->attr('datatype');
			$attr_id = (isset($attr_id) && is_int($attr_id)) ? $attr_id : null;
		}
		$this->tree($id);
		$this->setTemplateVars([
				'dtype'=> $dtype,
				'atype'=> $atype,
				'attr_id'=> $attr_id
			]);
	}

	function layout($id=null,$atype_id=null,$attr_id=null,$mode=null) {
		$node = Node::find($id);
		$section = Section::find(['node_id'=>(int)$id],1);
		if(Utils::is('POST')) {
			$this->renderMode('raw');
			$markup = isset($_POST['markup']) ? $_POST['markup'] : false;
			if($section && $markup) {
				$section->attr('markup',$markup);
				if($section->save()) {
					Utils::sendResponseCode('200');
				} else {
					Utils::sendResponseCode('500');
				}
			} else {
				Utils::sendResponseCode('404');
			}
		} else {
			$dtype = null;
			$atype = null;
			if(!isset($mode)) {
				if(isset($attr_id) && !is_int($attr_id)) {
					$mode = $attr_id;
				} elseif(isset($atype_id) && !is_int($atype_id)) {
					$mode = $atype_id;
				} else {
					$mode = 'content';
				}
			}
			if(isset($atype_id) && is_int($atype_id)) {
				$atype = Attributetype::find($atype_id);
				$dtype = $atype->attr('datatype');
				$attr_id = (isset($attr_id) && is_int($attr_id)) ? $attr_id : null;
			}
			$this->tree($node);
			if(!$section && $node->layout()) {
				$layout_id = $node->layout()->attr('id');
				$section = new Section($id,$layout_id);
			}
			# This should go
			$publication = Publication::find(1);
			if(!$publication) {
				 $publication = new Publication();
				 $publication->save();
			}
			$this->setTemplateVars([
					'publication' => $publication
				]);
			# ---
			$this->setTemplateVars([
					'page_cls'=> $mode,
					'dtype'=> $dtype,
					'atype'=> $atype,
					'attr_id'=> $attr_id,
					'section'=> $section
				]);
		}
	}

	function reset($id) {
		$section = Section::find(['node_id'=>$id],1);
		if($section->delete()) {
			Utils::redirect(DOMAIN.'/admin/nodes/'.Nn::settings('NODE_VIEW').'/'.$id);
		}
	}

	function toggle() {
		$this->renderMode('RAW');
		$visible = $_POST['visible'];
		$node = Node::find($_POST['id']);
		$node->attr('visible',$visible);
		if(!$node->save()) {
			Utils::sendResponseCode(500);
		}
		Utils::sendResponseCode(200);
	}
	
	function sort() {
		$this->renderMode('raw');
		$nodes = $_POST['nodes'];
		$parent_id = $_POST['parent_id'];
		for($i = 0; $i < count($nodes); $i++) {
			$node = Node::find($nodes[$i]);
			$node->attr('position',$i);
			$node->attr('parent_id',$parent_id);
			if(!$node->save()) {
				Utils::sendResponseCode(500);
			}
		}
		Utils::sendResponseCode(200);
	}
	
	function make($in=null,$parent_id=null) {
		if(isset($parent_id) && $parent_id != 0) {
			$parent = Node::find($parent_id);
			$nodetypes = $parent->nodetype()->nodetypes();
		} else {
			$parent_id = 0;
			$nodetypes = Nodetype::find(['can_be_root'=>1],null,'position');
		}
		$node = new Node();
		$node->attr('parent_id',$parent_id);
		$this->tree($node);
		$this->setTemplateVars([
				'nodetypes'=> $nodetypes
			]);
	}
	
	function edit($id=null) {
		$node = Node::find($id);
		$parent_id = $node->attr('parent_id');
		if(isset($parent_id) && $parent_id > 0) {
			$parent = Node::find($parent_id);
			$nodetypes = $parent->nodetype()->nodetypes();
		} else {
			$parent_id = 0;
			$nodetypes = Nodetype::find_all();
		}
		$parents = Node::find(array('-id'=>$node->attr('id')));
		$this->tree($node);
		$this->setTemplateVars([
				'parents'=> $parents,
				'nodetypes'=> $nodetypes
			]);
	}
	
	function create() {
		$node = new Node($_POST['title'],Nn::session()->user_id(),$_POST['parent_id'],$_POST['nodetype_id']);
		if($node->save()) {
			Nn::cache()->flush('api_getPosts_');
			Utils::redirect(DOMAIN.'/admin/nodes/'.Nn::settings('NODE_VIEW').'/'.$node->attr('id'));
		} else {
			Utils::redirect(DOMAIN.'/admin/nodes/make');
		}
	}
	
	function update($id=null) {
		$node = Node::find($id);
		$parent_id = isset($_POST['parent_id']) ? $_POST['parent_id'] : null;
		$node->fill($_POST['title'],$_POST['permalink'],$parent_id,$_POST['nodetype_id']);
		if($node->save()) {
			Nn::cache()->flush('api_getPosts_');
			Utils::redirect(DOMAIN.'/admin/nodes/'.Nn::settings('NODE_VIEW').'/'.$id);
		} else {
			Utils::redirect(DOMAIN.'/admin/nodes/edit/'.$id);
		}
	}
	
	function delete($id=null) {
		$node = Node::find($id);
		$parent_id = $node->attr('parent_id');
		$redirect_path = ($parent_id != 0) ? '/admin/nodes/'.Nn::settings('NODE_VIEW').'/'.$parent_id : '/admin/nodes';
		if($node->recursive_delete()) {
			Nn::cache()->flush('api_getPosts_');
			Utils::redirect(DOMAIN.$redirect_path);
		} else {
			die(print_r($node->errors));
		}
	}
}