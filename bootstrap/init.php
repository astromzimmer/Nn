<?php

/* define site path constant here to get absolute path */
$index_path = realpath(dirname((dirname(__FILE__))));
defined('ROOT') ? null : define('ROOT', $index_path);

// function namespace_autoload($class_name) {
// 	$class_file = str_replace('\\', DS, $class_name).'.php';
// 	if(is_readable(ROOT.DS.$class_file)) {
// 		include_once ROOT.DS.$class_file;
// 	} elseif(is_readable(ROOT.DS.'Nn'.DS.$class_file)) {
// 		include_once ROOT.DS.'Nn'.DS.$class_file;
// 	} elseif (is_readable(ROOT.DS.'vendor'.DS.$class_file)) {
// 		include_once ROOT.DS.'vendor'.DS.$class_file;
// 	}
// }
// spl_autoload_register('namespace_autoload');

require ROOT.DS.'vendor'.DS.'autoload.php';
require ROOT.DS.'bootstrap'.DS.'config.php';
require ROOT.DS.'Nn'.DS.'Nn.php';
require ROOT.DS.'Nn'.DS.'Utils.php';

$session = new Nn\Core\Session();
$minify = new Nn\Core\Minify();
$cache = new Nn\Core\Cache();
$mailer = new Nn\Core\Mailer();
$router = new Nn\Core\Router();
$storage = new Nn\Storage\PDOStorage();
$language = new Nn\Babel\Dictionary();
$tracker = new Nn\Trackers\Navajo();

Nn::init($session, $minify, $cache, $mailer, $router, $storage, $language, $tracker);

require ROOT.DS.'bootstrap'.DS.'routes.php';

Nn::run();