<?php

namespace Nn\Modules\Publication;
use Nn\Modules\Node\Node as Node;
use Nn;
use Utils;

class Publication extends Nn\Core\DataModel {
	
	protected $title;

	public static $SCHEMA = array(
			'title' => 'short_text',
			'nodes' => 'text',
			'created_at' => 'float',
			'updated_at' => 'float'
		);

	public function exportProperties($excludes=array()) {
		return array(
			'title'		=>	$this->title,
			'content'		=>	$this->content,
			'created_at'	=>	$this->created_at,
			'updated_at'	=>	$this->updated_at
		);
	}
	
	public function __construct($title=null,$content=null){
		if(!empty($title)){
			$this->title = $title;
			$this->content = $content;
			return $this;
		} else {
			return false;
		}
	}

	public function coverTree() {
		$nodes = self::nodes(true);
		if($nodes) {
			$kgv = [];
			$name_trees = [];
			foreach($nodes as $node) {
				$name_tree = [];
				$obj_tree = $node->navigation_tree();
				foreach($obj_tree as $obj) {
					array_push($name_tree, $obj->attr('title'));
				}
				array_push($name_trees, $name_tree);
			}
			if(count($name_trees) > 1) {
				usort($name_trees,function($a,$b) {
					return count($b)-count($a);
				});
				// $params = array_merge($name_trees,[function($a,$b){
				// 	return $a == $b;
				// }]);
				// $kgv = call_user_func_array('array_uintersect', $params);
				$kgv = array_shift($name_trees);
				foreach($name_trees as $name_tree) {
					if((count($name_tree) < count($kgv)) && !array_diff($name_tree, $kgv)) {
						// placeholder
					} else {
						$kgv = array_intersect($kgv, $name_tree);
					}
				}
			} else {
				$kgv = $name_trees[0];
			}		
			return $kgv;
		} else {
			return false;
		}
	}

	public function addNode($node_id,$bubbling) {
		$nodes_array = json_decode($this->nodes,true);
		$nodes_array[(string)$node_id] = $bubbling;
		$this->nodes = json_encode($nodes_array);
	}

	public function removeNode($node_id) {
		$nodes_array = json_decode($this->nodes,true);
		unset($nodes_array[$node_id]);
		$this->nodes = json_encode($nodes_array);
	}

	public function nodes($only_bubbling=false) {
		$result = [];
		if(isset($this->nodes) && $this->nodes != '') {
			// WAY FASTER, but not sorted
			// $query = ['id'=>Utils::explode(',', $this->nodes)];
			// $result = Node::find($query,null,'position');
			$node_pairs = json_decode($this->nodes,true);
			foreach($node_pairs as $node_id=>$bubbling) {
				if(!$only_bubbling || $bubbling) {
					$node = Node::find($node_id);
					if($node) {
						$node->bubbling = $bubbling;
						array_push($result, $node);
					}
				}
			}
		}
		return $result;
	}

	public function layouts() {
		return Layout::find(['publication_id'=>$this->id],1);
	}

}

?>