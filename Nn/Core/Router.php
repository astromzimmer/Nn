<?php

namespace Nn\Core;
use Nn;
use Utils;

class Route {
	public $pattern;
	public $method;
	public $callback;

	public function __construct($pattern,$method,$callback) {
		if(isset($pattern)) $this->pattern = $pattern;
		if(isset($method)) $this->method = $method;
		if(isset($callback)) $this->callback = $callback;
	}
}

class Router {

	private $default_controller;
	private $default_action;
	private $module;
	private $controller;
	private $action;
	private $routes;

	public function __construct() {
		$this->routes = array();
	}

	public function setDefaultController($controller) {
		$this->default_controller = $controller;
	}

	public function setDefaultAction($action) {
		$this->default_action = $action;
	}

	public function getController() {
		return $this->controller;
	}

	public function getAction() {
		return $this->action;
	}

	public function get($pattern,$callback) {
		self::set($pattern,'get',$callback);
	}

	public function post($pattern,$callback) {
		self::set($pattern,'post',$callback);
	}

	private function set($pattern,$method,$callback) {
		$this->routes[] = new Route($pattern,$method,$callback);
	}

	public function route($route_param) {
		header("Redirect: {$route_param}");
		$method = $_SERVER['REQUEST_METHOD'];
		if($route = $this->reRoute($route_param,$method)) {
			$this->execute($route->callback);
		} else {
			$this->execute($route_param);
		}
	}

	private function reRoute($route_param,$method) {
		foreach($this->routes as $route) {
			if(preg_match($route->pattern, $route_param) && strtoupper($route->method) == $method) {
				$route->callback = preg_replace($route->pattern, $route->callback, $route_param);
				return $route;
			}
		}
		return false;
	}

	private function execute($callback=null) {
		if(isset($callback)) {
			if(is_callable($callback)) {
				return call_user_func($callback);
			} else {
				$controller = $this->probeController($callback);
			}
		} else {
			$controller = $this->probeController();
		}
	}

	private function probeController($target_path=null) {
		$found_it = false;
		$this->module = Utils::singularise(ucwords($this->default_controller));
		$this->controller = ucwords($this->default_controller).'Controller';
		$this->action = $this->default_action;
		$query = array();
		if(isset($target_path)) {
			$target_path_array = explode("/", $target_path);
			$plural = ucwords(array_shift($target_path_array));
			$this->module = Utils::singularise($plural);
			$potential_controller_name = $plural.'Controller';
			$this->controller = $potential_controller_name;
			$potential_action = array_shift($target_path_array);
			if(!empty($potential_action)) $this->action = $potential_action;
			$query = $target_path_array;
		}
		if(file_exists(ROOT.DS.'App'.DS.$this->module.DS.$this->controller.'.php')) {
			$controllerClass = 'App\\'.$this->module.'\\'.$this->controller;
			$found_it = method_exists($controllerClass, $this->action);
		}
		if(!$found_it && file_exists(ROOT.DS.'Nn'.DS.'Modules'.DS.$this->module.DS.$this->controller.'.php')) {
			$controllerClass = 'Nn\\Modules\\'.$this->module.'\\'.$this->controller;
			$found_it = method_exists($controllerClass, $this->action);
		}
		if(!$found_it) {
			$controllerClass = 'Nn\\Modules\\'.$this->module.'\\DefController';
			$this->action = 'notFound';
		}
		if($this->controller == 'PublikController' || $this->controller == 'ApiController') {
			Nn::settings('HIDE_INVISIBLE',true);
			# Best place for tracker?
			Nn::track();
		} else {
			Nn::settings('HIDE_INVISIBLE',false);
		}
		$controller = new $controllerClass($this->action,$query);
		call_user_func_array(array($controller, 'before'), $query);
		call_user_func_array(array($controller, $this->action), $query);
		call_user_func_array(array($controller, 'after'), $query);
	}

}