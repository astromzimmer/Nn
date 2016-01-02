<?php

namespace Nn\Modules\Attribute;
use Nn\Modules\Node\Node as Node;
use Nn\Modules\Attributetype\Attributetype as Attributetype;
use Nn;
use Utils;

class Attribute extends \Nn\Core\DataModel {
	
	protected $id;
	protected $position;
	protected $node_id;
	protected $data_id;
	protected $attributetype_id;
	protected $visible;
	protected $created_at;
	protected $updated_at;

	public static $SCHEMA = array(
			'position' => 'integer',
			'data_id' => 'integer',
			'node_id' => 'integer',
			'attributetype_id' => 'integer',
			'visible' => 'integer',
			'created_at' => 'float',
			'updated_at' => 'float'
		);

	public function __construct($node_id=null, $attributetype_id=null, $data_id=null, $visible=1){
		if(!empty($node_id) && !empty($attributetype_id) && !empty($data_id)) {
			$this->node_id = (int)$node_id;
			$this->position = 2147483647;
			$this->attributetype_id = (int)$attributetype_id;
			$this->data_id = (int)$data_id;
			$this->visible = !Nn::settings('SAFE_PUBLISHING');
			return $this;
		} else {
			return false;
		}
	}
	
	public function public_view() {
		$partial_suggestion = $this->datatype().DS.'views'.DS."_".str_replace(" ","_",strtolower($this->attributetype()->attr('name')));
		$partial = Utils::fileExists([
				ROOT.DS.'App'.DS.$partial_suggestion.'.jade',
				ROOT.DS.'App'.DS.$partial_suggestion.'.php',
				ROOT.DS.'Nn'.DS.'Modules'.DS.$partial_suggestion.'.jade',
				ROOT.DS.'Nn'.DS.'Modules'.DS.$partial_suggestion.'.php',
				ROOT.DS.'App'.DS.$this->datatype().DS.'views'.DS.'_view.jade',
				ROOT.DS.'App'.DS.$this->datatype().DS.'views'.DS.'_view.php',
				ROOT.DS.'Nn'.DS.'Modules'.DS.$this->datatype().DS.'views'.DS.'_view.jade',
				ROOT.DS.'Nn'.DS.'Modules'.DS.$this->datatype().DS.'views'.DS.'_view.php'
			]);
		return $partial;
	}

	public function admin_view() {
		$partial = Utils::fileExists([
				ROOT.DS.'App'.DS.$this->datatype().DS.'views'.DS.'_view.jade',
				ROOT.DS.'App'.DS.$this->datatype().DS.'views'.DS.'_view.php',
				ROOT.DS.'Nn'.DS.'Modules'.DS.$this->datatype().DS.'views'.DS.'_view.jade',
				ROOT.DS.'Nn'.DS.'Modules'.DS.$this->datatype().DS.'views'.DS.'_view.php'
			]);
		return $partial;
	}
	
	public function node() {
		return Node::find($this->node_id);
	}
	
	public function data() {
		$datatype = $this->datatype();
		$datatype_class = 'Nn\\Modules\\'.$datatype.'\\'.$datatype;
		$data_id = $this->data_id;
		try {
			# Needs better error handling for module updates
			$data = $datatype_class::find($data_id);
			return $data;
		} catch(Exception $e) {
			return false;
		}
	}
	
	public function datatype() {
		$attributetype = $this->attributetype();
		return $attributetype->attr('datatype');
	}
	
	public function attributetype() {
		return Attributetype::find($this->attributetype_id);
	}

	public function exportProperties($excludes=array()) {
		return array(
			'id'			=>	$this->id,
			'uid'			=>	sha1($this->id.$this->created_at),
			'position'		=>	$this->position,
			'data'			=>	$this->data(),
			'attributetype'	=>	$this->attributetype(),
			'created_at'	=>	$this->created_at,
			'updated_at'	=>	$this->updated_at,
			'node_id'		=>	$this->node_id,
			'visible'		=>	$this->visible
		);
	}
	
	public static function find_all_of($node_id){
		$query = array('node_id'=>$node_id);
		$result = static::find($query);
		return $result;
	}
	
	public function delete() {
		$data = $this->data();
		if($data) {
			if(!$data->delete()) {
				return false;
			}
		}
		if(!parent::delete()) {
			return false;
		}
		return true;
	}
}

?>