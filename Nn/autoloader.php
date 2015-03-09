<?php

// // WILL SOON BE DEPRECATED
// function vintage_autoload($class_name){
// 	$filename = strtolower($class_name);
// 	$lib_path = ROOT.DS.'lib'.DS.$filename.'.master.php';
// 	$controllers_path = ROOT.DS.'app'.DS.'controllers'.DS.$class_name.'.php';
// 	$models_path = ROOT.DS.'app'.DS.'models'.DS.$filename . '.class.php';
// 	if(file_exists($lib_path)){
// 		include_once($lib_path);
// 	} else if(file_exists($controllers_path)){
// 		include_once($controllers_path);
// 	} else if(file_exists($models_path)){
// 		include_once($models_path);
// 	} else {
// //		die("AUTOLOAD ERROR: the file {$filename}.php could not be found.");
// 	}
// }

function namespace_autoload($class_name) {
	$class_file = str_replace('\\', DS, $class_name).'.php';
	if(is_readable(ROOT.DS.$class_file)) {
		include_once ROOT.DS.$class_file;
	} elseif(is_readable(ROOT.DS.'Nn'.DS.$class_file)) {
		include_once ROOT.DS.'Nn'.DS.$class_file;
	} elseif (is_readable(ROOT.DS.'vendor'.DS.$class_file)) {
		include_once ROOT.DS.'vendor'.DS.$class_file;
	}
}

// spl_autoload_register('vintage_autoload');
spl_autoload_register('namespace_autoload');