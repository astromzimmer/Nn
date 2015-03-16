<?php

$index_path = realpath(dirname((dirname(__FILE__))));
defined('ROOT') ? null : define('ROOT', $index_path);
defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);

$init_path = dirname(__DIR__).DS.'bootstrap'.DS.'init.php';

require_once $init_path;