<?php

namespace Nn\Modules\Text;
use Nn;

class Text extends Nn\Modules\Datatype\Datatype {
	
	protected $content;
	protected $markup;

	public static $SCHEMA = array(
			'content' => 'long_text',
			'markup' => 'long_text',
			'created_at' => 'float',
			'updated_at' => 'float'
		);

	public static $PARAMS = array(
			'size' => ['short','long']
		);

	public static $DEFAULT = 'textarea';

	public function exportProperties() {
		return array(
			'id'			=>	$this->id,
			'content'		=>	$this->content(),
			'created_at'	=>	$this->created_at,
			'updated_at'	=>	$this->updated_at
		);
	}
	
	public function content($raw=false) {
		if($raw) return $this->content;
		if($this->markup == '') $this->markup = $this->content;
		return str_replace("\"", "'", htmlspecialchars_decode($this->markup));
	}
	
	public function tagged_content() {
		return tagged($this->content());
	}

	public function isRTE() {
		if($this->id) {
			$result = (array_key_exists('rte',$this->attributetype()->params())) ? true : false;
		} else {
			$result = false;
		}
		return $result;
	}

	public function isLong() {
		return $this->attributetype()->params()['size'] == 'long';
	}
	
	public function __construct($content=null,$markup=null){
		if(!empty($content)){
			$this->content = $content;
			$this->markup = htmlspecialchars(str_replace("\n", "", $markup));
			return $this;
		} else {
			return false;
		}
	}
	
	public function fill($content=null,$markup=null){
		$this->content = $content;
		$this->markup = htmlspecialchars(str_replace("\n", "", $markup));
		return $this;
	}

}

?>