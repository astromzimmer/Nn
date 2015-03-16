<?php

namespace Nn\Modules\Nodetype;
use Nn\Modules\Attributetype\Attributetype as Attributetype;
use Nn;
use Utils;

class Nodetype extends Nn\Core\DataModel {
	
	protected $id;
	protected $name;
	protected $attributetypes;
	protected $nodetypes;
	protected $position;

	public static $SCHEMA = array(
			'name' => 'short_text',
			'attributetypes' => 'text',
			'nodetypes' => 'text',
			'position' => 'integer',
			'created_at' => 'integer',
			'updated_at' => 'integer'
		);
	
	public function __construct($name=null, $attributetypes=null, $nodetypes=null){
		if(isset($name)){
			$this->name = $name;
			$this->attributetypes = (!empty($attributetypes)) ? implode(",",$attributetypes) : "";
			$this->nodetypes = (!empty($nodetypes)) ? implode(",",$nodetypes) : "";
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
		$query = array('id'=>Utils::explode(',', $this->attributetypes));
		$result = Attributetype::find($query,null,'position');
		return $result;
	}

	public function has_nodetype($atype) {
		$nodetype_id = is_object($atype) ? $atype->id : $atype;
		return in_array($nodetype_id, Utils::explode(',', $this->nodetypes));
	}

	public function nodetypes() {
		$query = array('id'=>Utils::explode(',', $this->nodetypes));
		$result = self::find($query,null,'position');
		return $result;
	}
	
}

?>