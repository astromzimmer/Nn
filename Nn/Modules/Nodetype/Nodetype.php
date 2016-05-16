<?php

namespace Nn\Modules\Nodetype;
use Nn\Modules\Attributetype\Attributetype as Attributetype;
use Nn\Modules\Layout\Layout as Layout;
use Nn;
use Utils;

class Nodetype extends Nn\Core\DataModel {
	
	protected $id;
	protected $name;
	protected $icon;
	protected $attributetypes;
	protected $can_be_root;
	protected $nodetypes;
	protected $layout_id;
	protected $position;

	public static $SCHEMA = array(
			'name' => 'short_text',
			'icon' => 'short_text',
			'attributetypes' => 'text',
			'nodetypes' => 'text',
			'layout_id' => 'integer',
			'can_be_root' => 'integer',
			'position' => 'integer',
			'created_at' => 'float',
			'updated_at' => 'float'
		);
	
	public function __construct($name=null, $icon=null, $can_be_root=null, $attributetypes=null, $nodetypes=null, $layout_id=null){
		if(isset($name)){
			$this->name = $name;
			$this->icon = $icon;
			$this->position = 2147483647;
			$this->attributetypes = (!empty($attributetypes)) ? implode(",",$attributetypes) : "";
			$this->nodetypes = (!empty($nodetypes)) ? implode(",",$nodetypes) : "";
			$this->layout_id = $layout_id;
			$this->can_be_root = $can_be_root;
			return $this;
		} else {
			return false;
		}
	}

	public function name() {
		return $this->name;
	}
	
	public function find_by_name($name=null) {
		$attributes = 'name';
		$vals = $name;
		$result = $this->find($sql,$vals,null,'position');
		return $result;
	}

	public function has_attributetype($atype) {
		$attributetype_id = is_object($atype) ? $atype->id : $atype;
		return in_array($attributetype_id, Utils::explode(',', $this->attributetypes));
	}
	
	public function attributetypes() {
		$result = false;
		if($this->attributetypes != '') {
			$query = array('id'=>Utils::explode(',', $this->attributetypes));
			$result = Attributetype::find($query,null,'position');
		}
		return $result;
	}

	public function has_nodetype($atype) {
		$nodetype_id = is_object($atype) ? $atype->id : $atype;
		return in_array($nodetype_id, Utils::explode(',', $this->nodetypes));
	}

	public function nodetypes() {
		$result = false;
		if($this->nodetypes != '') {
			$query = array('id'=>Utils::explode(',', $this->nodetypes));
			$result = self::find($query,null,'position');
		}
		return $result;
	}

	public function layout() {
		return Layout::find($this->layout_id);
	}

	public function canBeRoot() {
		return $this->can_be_root;
	}
	
}

?>