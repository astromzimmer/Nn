<?php

namespace Nn\Modules\Node;
use Nn\Modules\Nodetype\Nodetype as Nodetype;
use Nn\Modules\Attribute\Attribute as Attribute;
use Nn\Modules\Attributetype\Attributetype as Attributetype;
use Nn\Modules\Publication\Section as Section;
use Nn\Modules\User\User as User;
use Nn;
use Utils;

class Node extends Nn\Core\DataModel {
	
	# Practice: always set init values, for class property casting
	protected $id;
	protected $slug;
	protected $position;
	protected $visible;
	protected $title;
	protected $nodetype_id;
	protected $author_id;
	protected $created_at;
	protected $updated_at;
	protected $parent_id;

	public static $SCHEMA = array(
			'slug' => 'short_text',
			'position' => 'integer',
			'visible' => 'integer',
			'title' => 'short_text',
			'nodetype_id' => 'integer',
			'author_id' => 'integer',
			'created_at' => 'float',
			'updated_at' => 'float',
			'parent_id' => 'integer'
		);

	public function exportProperties() {
		return array(
			'id'			=>	$this->id,
			'uid'			=>	'N_'.$this->id,
			'slug'			=>	$this->slug(),
			'position'		=>	$this->position,
			'raw_title'		=>	$this->title(true),
			'source'		=>	'node',
			'title'			=>	$this->title(),
			'path'			=>	$this->path(),
			'thumbnail'		=>	($thumb = $this->thumb()) ? [
										'id' => $thumb->attr('id'),
										'filename' => $thumb->attr('filename')
									] : false,
			'ingress'		=>	($ingress = $this->ingress()) ? $ingress->content() : false,
			'permalink'		=>	$this->permalink(),
			'nodetype'		=>	$this->type(),
			'author'		=>	$this->author(),
			'timestamp'		=>	$this->timestamp(),
			'created_at'	=>	$this->created_at,
			'updated_at'	=>	$this->updated_at,
			'created_year'	=>	date('Y',$this->timestamp()),
			'created_week'	=>	idate('W',$this->timestamp()),
			'node'			=>	($parent = $this->parent()) ? [
										'id'=>$this->parent_id,
										'title'=>$parent->title(),
										'slug'=>$parent->slug()
									] : false,
			'ownAttribute'	=>	$this->attributes_except('Date'),
			'ownNode'		=>	($children = $this->children_by_type('container,person,city')) ? $children : []
		);
	}

	public function __construct($title=null, $author_id=null, $parent_id=null, $nodetype_id=null){
		if(!empty($title) && !empty($author_id)){
			$this->title = $title;
			$this->position = 2147483647;
			$this->author_id = (int)$author_id;
			$this->parent_id = (int)$parent_id;
			$this->nodetype_id = (int)$nodetype_id;
			$this->visible = !Nn::settings('SAFE_PUBLISHING');
			return $this;
		} else {
			return false;
		}
	}

	public function fill($title=null, $slug=null, $parent_id=null, $nodetype_id=null){
		if(isset($title)) $this->title = (string)$title;
		if(isset($slug)) $this->slug = (string)$slug;
		if(isset($parent_id)) $this->parent_id = (int)$parent_id;
		if(isset($nodetype_id)) $this->nodetype_id = (int)$nodetype_id;
		return $this;
	}

	public function save() {
		parent::save();
		return Nn::cache()->flush('api_getPosts_');
	}
	
	public function title($raw=false) {
		$t = $this->attribute('title');
		return ($t) ? $t->data()->content($raw) : htmlspecialchars_decode($this->title);
	}

	public function thumb() {
		$thumb = $this->attribute_by_datatype('Image');
		if($thumb) return $thumb->data();
		return false;
	}

	public function ingress() {
		$ingress = $this->attribute_by_datatype('Text');
		if($ingress) return $ingress->data();
		return false;
	}

	public function images() {
		$images = array();
		foreach($this->attributes("image") as $image_attribute) {
			$images[] = $image_attribute->data();
		}
		return $images;
 	}
 	
	public function height() {
		$height = 0;
		foreach($this->images() as $image) {
			$height = ($image->size("height") > $height) ? $image->size("height") : $height;
		}
		return ($height > 0) ? $height : 450;
	}
	
	public function width() {
		$width = 0;
		foreach($this->images() as $image) {
			$width += $image->size("width");
		}
		return ($width > 0) ? $width : 450;
	}

	public function timestamp() {
		$integer = $this->attribute_by_datatype('Integer');
		if($integer && $integer->attributetype()->param('format') == 'timestamp') {
			$timestamp = $integer->data()->attr('number');
		} else {
			$timestamp = $this->created_at;
		}
		return $timestamp;
	}
	
	public function slug() {
		if(!isset($this->slug) || empty($this->slug)) {
			$this->slug = $this->id."-".preg_replace("/[^a-zA-Z0-9s-]/","_",$this->title);
			$this->save();
		}
		return $this->slug;
	}

	public function permalink($abs=false) {
		$navigation_tree = $this->navigation_tree();
		$permalink = $abs ? DOMAIN : '';
		foreach($navigation_tree as $parent) {
			$permalink .= '/'.$parent->slug();
		}
		$permalink .= '/'.$this->slug();
		return $permalink;
	}
	
	public function type() {
		return $this->nodetype()->attr('name');
	}
	
	public function navigation_tree() {
		$p_node = $this;
		$tree = [];
		while($p_node->parent()) {
			$p_node = $p_node->parent();
			$tree[] = $p_node;
		}
		$tree = array_reverse($tree);
		return $tree;
	}

	public function path() {
		$tree = $this->navigation_tree();
		$path = '';
		foreach ($tree as $branch) {
			$path .= Utils::ellipsis($branch->title(),24).'/';
		}
		$path .= Utils::ellipsis($this->title(),24);
		return $path;
	}

	public function children($title=null) {
		$query = (isset($title)) ? array('parent_id'=>$this->id,'title'=>$title) : array('parent_id'=>$this->id);
		$result = static::find($query,null,'position');
		return $result;
	}
	
	public function first_content() {
		return ($this->attributes() || !$this->first_child()) ? $this : $this->first_child()->first_content();
	}
	
	public static function find_by_type($nodetypes=null,$limit=null) {
		$nodetypes_array = array_map('trim',explode(',', $nodetypes));
		$nodetypes = Nodetype::find(['name'=>$nodetypes_array]);
		if($nodetypes) {
			$nodetype_ids = static::getIDs($nodetypes);
			$nodes = static::find(array('nodetype_id'=>$nodetype_ids),$limit,'position');
			return $nodes;
		}
		return false;
	}

	public function first_child() {
		return static::find('parent_id',$this->id,1,'position');
	}
	
	public function children_by_type($nodetypes=null) {
		$nodetypes_array = array_map('trim',explode(',', $nodetypes));
		$nodetypes = Nodetype::find(['name'=>$nodetypes_array]);
		if($nodetypes) {
			$nodetype_ids = static::getIDs($nodetypes);
			$query = array('parent_id'=>$this->id,'nodetype_id'=>$nodetype_ids);
			$nodes = static::find($query,null,'position');
			return $nodes;
		}
		return false;
	}
	
	public function recursive_children($nodetypes=null) {
		$valid_children = array();
		$children = (isset($nodetypes)) ? static::find_by_type($nodetypes) : static::find_all();
		if($children) {
			foreach($children as $child) {
				if($child->attributes() && in_array($this,$child->navigation_tree())) {
					$valid_children[] = $child;
				}
			}
		}
		// sort($valid_children);
		return $valid_children;
	}
	
	public function attributes_except($attributetype_names=null,$limit=null) {
		if(empty($attributetype_names)) return Attribute::find(array('node_id'=>$this->id),$limit,'position');
		$attributetype_names = (is_array($attributetype_names)) ? $attributetype_names : Utils::explode(',',$attributetype_names);
		$attributetypes = Attributetype::find(array('-name'=>$attributetype_names));
		if($attributetypes) {
			$attributetype_ids = static::getIDs($attributetypes);
			$query = array('node_id'=>$this->id,'attributetype_id'=>$attributetype_ids);
			return Attribute::find($query,$limit,'position');
		}
		return false;
	}

	public function attributes($attributetype_names=null,$limit=null) {
		if(empty($attributetype_names)) return Attribute::find(array('node_id'=>$this->id),$limit,'position');
		$attributetype_names = (is_array($attributetype_names)) ? $attributetype_names : Utils::explode(',',$attributetype_names);
		$attributetypes = Attributetype::find(array('name'=>$attributetype_names));
		if($attributetypes) {
			$attributetype_ids = static::getIDs($attributetypes);
			$query = array('node_id'=>$this->id,'attributetype_id'=>$attributetype_ids);
			return Attribute::find($query,$limit,'position');
		}
		return false;
	}
	
	public function attribute($attributetype=null) {
		$result = $this->attributes($attributetype,1);
		return $result;
	}

	public function attribute_by_datatype($datatype=null) {
		if(isset($datatype)) {
			if($attributes = $this->attributes()) {
				foreach($attributes as $key => $value) {
					if($value->attributetype()->attr('datatype') == $datatype) return $value;
				}
			}
		}
		return false;
	}
	
	public function nodetype() {
		return ($this->nodetype_id != 0) ? Nodetype::find($this->nodetype_id) : Nodetype::find_all(1);
	}

	public function layout() {
		return $this->nodetype()->layout();
	}

	public function section() {
		return Section::find(['node_id'=>$this->id],1);
	}
	
	public function parent() {
		$parent = static::find($this->parent_id);
		return $parent;
	}
	
	public function author() {
		$user = new User();
		return $user->find($this->author_id);
	}
	
	public function comments() {
		$comment = new Comment();
		return $comment->find_all_on($this->id);
	}
	
	public function recursive_delete() {
		if($this->attributes()) {
			foreach($this->attributes() as $attribute) {
				if(!$attribute->delete()) {
					$this->errors[] = "failed to remove attribute: ".$attribute->id;
				}
			}
		}
		if($this->children()) {
			foreach($this->children() as $child) {
				if(!$child->recursive_delete()) {
					$this->errors[] = "failed to remove childnode: ".$child->title;
				}
			}
		}
		if($this->delete()) {
			return true;
		} else {
			return false;
		}
	}

}

?>