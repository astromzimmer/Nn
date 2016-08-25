<?php

namespace Nn\Modules\Layout;
use Nn;
use Utils;

class Layout extends Nn\Core\DataModel {
	
	protected $name;
	protected $rules;
	protected $template;
	protected $position;

	public static $SCHEMA = array(
			'name' => 'short_text',
			'rules' => 'long_text',
			'template' => 'long_text',
			'position' => 'integer',
			'created_at' => 'double',
			'updated_at' => 'double'
		);
	
	public function __construct($name=null,$rules=null,$template=null){
		if(!empty($name) && !empty($rules) && !empty($template)){
			$this->name = $name;
			$this->rules = $rules;
			$this->template = $template;
			$this->position = 2147483647;
			return $this;
		} else {
			return false;
		}
	}

}

?>