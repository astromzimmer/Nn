<?php

namespace Nn\Modules\Publication;
use Nn\Modules\Node\Node as Node;
use Nn;
use Utils;

class PublicationsController extends Nn\Core\Controller {

	protected $icons;

	function before() {
		Nn::authenticate();
	}
	
	function index() {
		$publication = Publication::find(1);
		if(!$publication) {
			 $publication = new Publication();
			 $publication->save();
		}
		$this->setTemplateVars([
				'publication' => $publication
			]);
	}

	function cart($id=null) {
		$publication = Publication::find(1);
		if(!$publication) {
			 $publication = new Publication();
			 $publication->save();
		}
		$this->setTemplateVars([
				'publication' => $publication
			]);
	}

	function cover($id=null) {
		if(!isset($publication_id)) $publication_id = 1;
		$publication = Publication::find($publication_id);
		$tree = $publication->coverTree();
	}

	function view($id=null) {
		$nodes = Node::find(array('parent_id'=>0),null,'position');
		$publication = Publication::find(1);
		if(!$publication) {
			 $publication = new Publication();
			 $publication->save();
		}
		$this->setTemplateVars([
				'page_cls'=> 'publication',
				'nodes' => $nodes,
				'publication' => $publication
			]);
	}

	function set($publication_id=null) {
		$this->renderMode('raw');
		parse_str(file_get_contents('php://input'),$_PUT);
		if(isset($_PUT['node_id'])) {
			if(!isset($publication_id)) $publication_id = 1;
			$bubbling = (!isset($_PUT['bubbling'])) ? true : intval($_PUT['bubbling']);
			$publication = Publication::find($publication_id);
			$publication->addNode($_PUT['node_id'],$bubbling);
			if($publication->save()) {
				Utils::redirect_to(DOMAIN.'/admin/publications/cart/'.$publication->attr('id'));
			}
		}
		Utils::sendResponseCode(500,"Something went wrong");
	}

	function remove($publication_id=null) {
		$this->renderMode('raw');
		parse_str(file_get_contents('php://input'),$_PUT);
		if(isset($_PUT['node_id'])) {
			if(!isset($publication_id)) $publication_id = 1;
			$publication = Publication::find($publication_id);
			$publication->removeNode($_PUT['node_id']);
			if($publication->save()) {
				Utils::redirect_to(DOMAIN.'/admin/publications/cart/'.$publication->attr('id'));
			}
		}
		Utils::sendResponseCode(500,"Something went wrong");
	}
	
	function sort() {
		$this->renderMode('raw');
		$flipped_ids = array_flip($_POST['sections']);
		$node_ids = array_combine(array_map(function($k){return ' '.$k;}, array_keys($flipped_ids)),$flipped_ids);
		$publication_id = $_POST['parent_id'];
		$publication = Publication::find($publication_id);
		$nodes = json_decode($publication->attr('nodes'),true);
		$prefixed_nodes = array_combine(array_map(function($k){return ' '.$k;}, array_keys($nodes)),$nodes);
		$new_nodes = array_merge($node_ids,$prefixed_nodes);
		$new_nodes = array_combine(array_map(function($k){return trim($k);}, array_keys($new_nodes)),$new_nodes);
		$publication->attr('nodes', json_encode($new_nodes));
		if(!$publication->save()) {
			Utils::sendResponseCode(500);
		}
		Utils::sendResponseCode(200);
	}
	
	function make() {
		
	}
	
	function create() {
		$title = isset($_POST['title']) ? $_POST['title'] : null;
		$nodes = isset($_POST['nodes']) ? $_POST['nodes'] : null;
		$publication = new Publication($title,$nodes);
		if($publication->save()) {
			Utils::redirect_to(DOMAIN.'/admin/publication');
		} else {
			die("failed to create publication");
		}
	}
	
	function edit($id=null) {
		
	}
	
	function update($id=null) {
		$title = isset($_POST['title']) ? $_POST['title'] : null;
		$nodes = isset($_POST['nodes']) ? $_POST['nodes'] : null;
		$publication = Publication::find($id);
		$publication->attr('title',$title);
		$publication->attr('nodes',$nodes);
		if($publication->save()) {
			Utils::redirect_to(DOMAIN.'/admin/publication');
		} else {
			die("failed to update publication");
		}
	}
	
	function delete($id=null) {
		$publication = Publication::find($id);
		if($publication->delete()) {
			Utils::redirect_to(DOMAIN.'/admin/publication');
		} else {
			die("failed to remove publication");
		}
	}
}

?>