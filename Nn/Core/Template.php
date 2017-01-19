<?php

namespace Nn\Core;
use Nn;
use Utils;

class Template extends Basic {

	protected $vars = array();
	protected $_dir;
	protected $_action;
	protected $render_as = "default";
	protected $content_type = 'text/html';
	
	function __construct($module,$action) {
		$this->_dir = $module.DS.'views';
		$this->_action = $action;
		if(isset($_GET['render_as'])) $this->render_as = $_GET['render_as'];
	}
	
	public function set($name, $value) {
		$this->vars[$name] = $value;
	}

	public function setFile($dir=null,$file=null) {
		if(isset($dir)) $this->_dir = $dir;
		if(isset($file)) $this->_action = $file;
	}

	public function content_type($ct) {
		$this->content_type = $ct;
	}
	
	public function render_as($mode,$ct=null) {
		if(isset($ct)) {
			$this->content_type = $ct;
		} else {
			switch($mode) {
				case 'default':
				case 'partial':
					$this->content_type = 'text/html';
					break;
				case 'pdf':
					$this->content_type = 'application/pdf';
					break;
				case 'binary':
					$this->content_type = 'application/octet-stream';
					break;
				case 'json':
					$this->content_type = 'application/json';
					break;
				case 'xml':
					$this->content_type = 'application/xml';
					break;
				case 'raw':
				default:
					$this->content_type = 'text/plain';
			}
		}
		$this->render_as = $mode;
	}

	private function getTemplatePath($extensionless_path) {
		$possibilities = [
				ROOT.DS.'App'.DS.$extensionless_path.'.jade',
				ROOT.DS.'App'.DS.$extensionless_path.'.php',
				ROOT.DS.'Nn'.DS.'Modules'.DS.$extensionless_path.'.jade',
				ROOT.DS.'Nn'.DS.'Modules'.DS.$extensionless_path.'.php'
			];
		return Utils::fileExists($possibilities);
	}

	private static function output($path,$vars=array()) {
		if($path){
			extract($vars);
			$extension = pathinfo($path,PATHINFO_EXTENSION);
			if($extension == 'jade') {
				$php_content = Utils::renderJade($path);
				$path = str_replace('.jade', '.php', $path);
				file_put_contents($path, $php_content);
			}
			include $path;
		}
	}

	private function renderTemplate() {
		if($this->render_as == 'partial') {
			$template = $this->getTemplatePath($this->_dir.DS.'_'.$this->_action);
		}
		if(!isset($template) || !$template) $template = $this->getTemplatePath($this->_dir.DS.$this->_action);
		if($template) {
			$this->output($template,$this->vars);
		} else {
			// Utils::redirect('/404.php');
		}
	}

	private function renderHeader() {
		if($header = $this->getTemplatePath($this->_dir.DS.'header')) {
			$this->output($header,$this->vars);
		} else {
			if(Nn::authenticated()) {
				$this->output($this->getTemplatePath('Admin'.DS.'views'.DS.'header'),$this->vars);
			} else {
				$this->output($this->getTemplatePath('Def'.DS.'views'.DS.'header'),$this->vars);
			}
		}
	}

	private function renderFooter() {
		if($footer = $this->getTemplatePath($this->_dir.DS.'footer')) {
			$this->output($footer,$this->vars);
		} else {
			if(Nn::authenticated()) {
				$this->output($this->getTemplatePath('Admin'.DS.'views'.DS.'footer'),$this->vars);
			} else {
				$this->output($this->getTemplatePath('Def'.DS.'views'.DS.'footer'),$this->vars);
			}
		}
	}
	
	public function render() {
		header('Content-Type: '.$this->content_type);
		switch($this->render_as) {
			case 'partial':
			case 'xml':
				$this->renderTemplate();
				break;
			case 'pdf':
				extract($this->vars);
				if(isset($data)) {
					header('Content-Transfer-Encoding: binary');
					header('Content-Disposition: inline');
					header('Content-Length: '.filesize($data));
					ob_clean();
					flush();
					readfile($data);
					exit;
				}
				break;
			case 'binary':
				extract($this->vars);
				if(isset($data)) {
					header('Content-Transfer-Encoding: binary');
					header('Content-Disposition: attachment; filename='.basename($data));
					header('Content-Length: '.filesize($data));
					ob_clean();
					flush();
					readfile($data);
					exit;
				}
				break;
			case 'image':
				extract($this->vars);
				if(isset($data)) {
					imagegif($data);
					imagedestroy($data);
				}
				break;
			case 'json':
				extract($this->vars);
				if(isset($data)) {
					if(Nn::s('DEVELOPMENT_ENV')) {
						$json_data = json_encode($data,JSON_PRETTY_PRINT);
					} else {
						$json_data = json_encode($data);
					}
					echo $json_data;
				}
				break;
			case 'raw':
				extract($this->vars);
				if(isset($data)) {
					echo $data;
				}
				break;
			default:
				$this->renderHeader();
				$this->renderTemplate();
				$this->renderFooter();
		}
	}

	public static function partial($module=null,$path=null,$vars=array()) {
		if(is_array($path) || !isset($path)) {
			if(is_array($path)) {
				$vars = $path;
			}
			$path = $module;
			$module = null;
		}
		$template = (!isset($module)) ? $path : Utils::fileExists([
				ROOT.DS.'App'.DS.$module.DS.'views'.DS.$path.'.jade',
				ROOT.DS.'App'.DS.$module.DS.'views'.DS.$path.'.php',
				ROOT.DS.'Nn'.DS.'Modules'.DS.$module.DS.'views'.DS.$path.'.jade',
				ROOT.DS.'Nn'.DS.'Modules'.DS.$module.DS.'views'.DS.$path.'.php'
			]);
		if($template) {
			self::output($template,$vars);
		}
	}

}