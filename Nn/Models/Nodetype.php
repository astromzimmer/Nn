<?php

namespace Nn\Models;
use Nn;
use Utils;

class Nodetype extends Nn\Core\DataModel {
	
	protected $id;
	protected $name;
	protected $attributetypes;
	protected $position;

	public static $SCHEMA = array(
			'name' => 'short_text',
			'attributetypes' => 'text',
			'position' => 'integer',
			'created_at' => 'integer',
			'updated_at' => 'integer',
		);
	
	public function __construct($name=null, $attributetypes=null){
		if(isset($name)){
			$this->name = $name;
			$this->attributetypes = (!empty($attributetypes)) ? implode(",",$attributetypes) : "";
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
	
}

?>