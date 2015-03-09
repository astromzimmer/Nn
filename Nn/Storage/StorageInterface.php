<?php

namespace Nn\Storage;

interface StorageInterface {
	
	public function count($location,$cast_to,$group,$query);

	public function find($location,$cast_to,$subset=null,$order_by=null);
	
	# $attribute, $value and $subset can be either arrays or singulars	
	public function find_by_attribute($location,$cast_to,$query,$subset=null,$order_by=null);
	
	public function save($location,$obj);
	
	public function delete($location,$obj);

	public function backup($filename=null);

	public function __sleep();

	public function __wakeup();

	public function __destruct();
}