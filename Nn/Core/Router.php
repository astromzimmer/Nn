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

	private $path;
	private $default_controller;
	private $default_action;
	private $module;
	private $controller;
	private $action;
	private $params;
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

	public function path() {
		return $this->path;
	}

	public function params() {
		return $this->params;
	}

	public function set($pattern,$method,$callback) {
		$this->routes[] = new Route($pattern,$method,$callback);
	}

	public function route() {
		$this->params = $_GET;
		$this->path = isset($this->params['route']) ? $this->params['route'] : null;
		unset($this->params['route']);
		$redirect = (count($this->params) > 0) ? $this->path.'?'.http_build_query($this->params) : $this->path;
		header("Redirect: {$redirect}");
		$method = $_SERVER['REQUEST_METHOD'];
		if($route = $this->reRoute($this->path,$method)) {
			$this->execute($route->callback);
		} else {
			$this->execute($this->path);
		}
	}

	private function reRoute($route_param,$method) {
		foreach($this->routes as $route) {
			if(preg_match($route->pattern, $route_param) && strtoupper($route->method) == $method) {
				// echo $route->pattern.'<br>';
				// echo $route->callback.'<br>';
				// echo $route_param.'<br>';
				$route->callback = preg_replace($route->pattern, $route->callback, $route_param, 1);
				Utils::debug($route->callback);
				// die($route->callback);
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
			$target_path_array = preg_split("/[\/:]/", $target_path);
			$plural = ucwords(array_shift($target_path_array));
			$this->module = Utils::singularise($plural);
			$potential_controller_name = $plural.'Controller';
			$this->controller = $potential_controller_name;
			$potential_action = array_shift($target_path_array);
			if(!empty($potential_action)) $this->action = $potential_action;
			$query = $target_path_array;
		}
		$controllerClass = false;
		$app_path = ROOT.DS.'App'.DS.$this->module.DS.$this->controller.'.php';
		$nn_path = ROOT.DS.'Nn'.DS.'Modules'.DS.$this->module.DS.$this->controller.'.php';
		$appControllerClass = (file_exists($app_path)) ? 'App\\'.$this->module.'\\'.$this->controller : false;
		$nnControllerClass = (file_exists($nn_path)) ? 'Nn\\Modules\\'.$this->module.'\\'.$this->controller : false;
		Utils::debug($appControllerClass);
		Utils::debug($this->action);
		if($appControllerClass && method_exists($appControllerClass, $this->action)) {
			$controller = new $appControllerClass($this->action,$query);
		} else if($nnControllerClass && method_exists($nnControllerClass, $this->action)) {
			$controller = new $nnControllerClass($this->action,$query);
		} else {
			Utils::redirect('/404');
		}
		if(Nn::authenticated() && isset($_GET['preview'])) {
			Nn::settings('HIDE_INVISIBLE', false);
		}
		call_user_func_array(array($controller, 'before'), $query);
		call_user_func_array(array($controller, $this->action), $query);
		call_user_func_array(array($controller, 'after'), $query);
	}

}