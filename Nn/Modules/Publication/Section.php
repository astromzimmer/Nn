<?php

namespace Nn\Modules\Publication;
use \Nn\Modules\Layout\Layout as Layout;
use Nn;

class Section extends Nn\Core\DataModel {
	
	protected $layout_id;
	protected $node_id;
	protected $markup;

	public static $SCHEMA = [
			'layout_id' => 'integer',
			'node_id' => 'integer',
			'markup' => 'long_text',
			'created_at' => 'float',
			'updated_at' => 'float'
		];

	public function markup() {
		if(empty($this->markup)) {
			$this->markup = $this->layout()->attr('markup');
			$this->save();
		}
		return $this->markup;
	}
	
	public function __construct($node_id=null,$layout_id=null,$markup=null){
		if(isset($node_id)) $this->node_id = $node_id;
		if(isset($layout_id)) $this->layout_id = $layout_id;
		if(isset($markup)) $this->markup = htmlspecialchars(str_replace("\n", "", $markup));
		return $this;
	}

	public function layout() {
		return Layout::find($this->layout_id,1);
	}

}

?>