<?php

namespace Nn\Modules\Datatype;
use Nn\Modules\Attribute\Attribute as Attribute;
use Nn\Modules\Attributetype\Attributetype as Attributetype;
use Nn;
use Utils;

class Datatype extends Nn\Core\DataModel {

	public function attributetype() {
		if(!isset($this->attributetype)) {
			$this->getAttributeAndType();
		}
		return $this->attributetype;
	}

	public function attribute() {
		if(!isset($this->attribute)) {
			if(!$this->getAttributeAndType()) return false;
		}
		return $this->attribute;
	}

	public function node() {
		return ($this->attribute()) ? $this->attribute()->node() : false;
	}

	public function save() {
		if(parent::save()) {
			if($node = $this->node()) {
				$node->save();
			}
			return true;
		}
		return false;
	}

	private function getAttributeAndType() {
		$possible_attributes = Attribute::find(['data_id'=>$this->attr('id')]);
		if(is_array($possible_attributes)) {
			foreach($possible_attributes as $possible_attribute) {
				$possible_attributetype = Attributetype::find([
					'id'=>$possible_attribute->attr('attributetype_id'),
					'datatype'=>$this->modelName()
				],1);
				if($possible_attributetype) {
					$this->attribute = $possible_attribute;
					$this->attributetype = $possible_attributetype;
					return true;
				}
			}
		}
		return false;
	}

}