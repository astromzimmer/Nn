<?php

namespace Nn\Modules\Attributetype;
use Nn;
use Utils;

class Attributetype extends Nn\Core\DataModel {
	
	protected $name;
	protected $datatype;
	protected $icon;
	protected $params;
	protected $position;

	public static $SCHEMA = array(
			'name' => 'short_text',
			'datatype' => 'short_text',
			'icon' => 'short_text',
			'params' => 'text',
			'default_value' => 'text',
			'position' => 'integer',
			'created_at' => 'double',
			'updated_at' => 'double',
		);

	public function exportProperties($excludes=array()) {
		return array(
			'name'			=>	$this->name,
			'datatype'		=>	$this->datatype,
			'icon'			=>	$this->icon,
			'params'		=>	$this->params
		);
	}

	public function __construct($name=null, $datatype=null, $icon=null, $params=null, $default_value=null){
		if(isset($name) && isset($datatype)){
			$this->name = $name;
			$this->datatype = $datatype;
			$this->icon = $icon;
			$this->position = 2147483647;
			if(isset($params)) $this->params = json_encode($params);
			if(isset($default_value)) $this->default_value = $default_value;
			return $this;
		} else {
			return false;
		}
	}

	public function cleanName() {
		return strtolower(str_replace(' ', '_', $this->name));
	}
	
	public function icon() {
		if($this->icon) {
			return $this->icon;
		} elseif($name_icon = Utils::UIIcon($this->name)) {
			return $name_icon;
		} elseif ($type_icon = Utils::UIIcon($this->datatype)) {
			return $type_icon;
		}
		return false;
	}
	
	public function params() {
		$json = json_decode($this->params,true);
		return (is_null($json)) ? array() : $json;
	}

	public function param($param) {
		return $this->params()[$param];
	}
	
	public function fill($name, $datatype, $icon, $params, $default_value){
		if(!empty($name) && !empty($datatype)){
			$this->name = $name;
			$this->datatype = $datatype;
			$this->icon = $icon;
			if(isset($params)) $this->params = json_encode($params);
			if(isset($default_value)) $this->default_value = $default_value;
			return $this;
		} else {
			return false;
		}
	}
	
}

?>