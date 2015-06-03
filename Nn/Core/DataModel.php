<?php

namespace Nn\Core;
use \Nn;
use \Utils;

abstract class DataModel extends Model {

	protected $id;
	protected $created_at;
	protected $updated_at;

	protected $_errors;

	public static $schema;

	public function errors() {
		return $this->_errors;
	}

	public function save() {
		return Nn::storage()->save(static::collectionName(),$this);
	}

	public static function count($group=null,$query=null,$respect_invisible=true) {
		if(!is_array($query)) {
			$query = array();
		}
		if(!isset($order_by)) $order_by = 'created_at';
		if(property_exists(get_called_class(), 'visible') && Nn::settings('HIDE_INVISIBLE') && $respect_invisible) $query['visible'] = 1;
		$result = Nn::storage()->count(static::collectionName(),get_called_class(),$group,$query);
		return array_shift($result);
	}

	public static function find_all($subset=null,$order_by=null,$respect_invisible=true) {
		if(!isset($order_by)) $order_by = 'created_at';
		if(property_exists(get_called_class(), 'visible') && Nn::settings('HIDE_INVISIBLE') && $respect_invisible) {
			$query = array('visible'=>1);
			return Nn::storage()->find_by_attribute(static::collectionName(),get_called_class(),$query,$subset,$order_by);
		}
		return Nn::storage()->find(static::collectionName(),get_called_class(),$subset,$order_by);
	}

	public static function find($query=null,$subset=null,$order_by=null,$respect_invisible=true) {
		if(!is_array($query)) {
			if(!isset($query)) {
				$query = [];
			} else {
				$query = array('id'=>$query);
			}
			$subset = 1;
		}
		if(!isset($order_by)) $order_by = 'created_at';
		if(property_exists(get_called_class(), 'visible') && Nn::settings('HIDE_INVISIBLE') && $respect_invisible) $query['visible'] = 1;
		$result = Nn::storage()->find_by_attribute(static::collectionName(),get_called_class(),$query,$subset,$order_by);
		return (is_array($result) && $subset == 1) ? array_shift($result) : $result;
	}

	public static function search($search,$query=array(),$subset=null,$order_by=null,$respect_invisible=true) {
		if(property_exists(get_called_class(), 'visible') && Nn::settings('HIDE_INVISIBLE') && $respect_invisible) $query['visible'] = 1;
		if(!isset($order_by)) $order_by = 'created_at';
		return Nn::storage()->search(static::collectionName(),get_called_class(),$search,$query,$subset,$order_by);
	}

	public static function modelName() {
		$model = get_called_class();
		$model_array = explode('\\',$model);
		$model_name = end($model_array);
		return $model_name;
	}

	public static function collectionName() {
		$model_name = self::modelName();
		$collection_name = strtolower(Utils::plurify($model_name));
		return $collection_name;
	}

	protected static function getIDs($models=array()) {
		$model_ids = array();
		$models = (is_array($models)) ? $models : array();
		foreach($models as $model) {
			$model_ids[] = $model->attr('id');
		}
		return $model_ids;
	}

	public function getAttributes() {
		$vars = get_object_vars($this);
		foreach($vars as $key=>$val) {
			if(substr($key, 0, 1) == '_') {
				unset($vars[$key]);
			}
		}
		return $vars;
	}

	public function date($d=null) {
		return strftime(Nn::settings('DATE_FORMAT'), (int)$this->timestamp($d));
	}

	public function timestamp() {
		$date = $this->created_at;
		return $date;
	}

	public function delete() {
		return Nn::storage()->delete(static::collectionName(),$this);
	}

}