<?php

namespace Nn\Modules\Style;
use Nn\Modules\Node\Node as Node;
use Nn;
use Utils;

class StylesController extends Nn\Core\Controller {
	
	function before() {
		$this->cache(['serve']);
	}

	function serve($name) {
		$styles = Style::find_all();
		$concat_data = '';
		$file_path = ROOT.DS.'public'.DS.'css'.DS.$name.'.css';
		if(file_exists($file_path)) {
			$content = file_get_contents($file_path);
			foreach($styles as $style) {
				$key = $style->attr('name');
				$val = $style->attr('value');
				$content = preg_replace("/{$key}/", $val, $content);
			}
			$minified_content = preg_replace('/\r|\n/', '', $content);
			$concat_data .= $minified_content;
		}
		$this->renderMode('raw','text/css');
		$this->setTemplateVars([
				'data'=>$concat_data
			]);
	}
	
	function index() {
		Nn::authenticate();
		$this->setTemplateVars([
				'styles'=> Style::find_all()
			]);
	}
	
	function view($id=null) {
		Nn::authenticate();
		$this->setTemplateVars([
				'style'=> Style::find($id)
			]);
	}

	function make() {
		Nn::authenticate();
		$this->setTemplateVars([
				'styles'=> Style::find_all()
			]);
		$this->setTemplateVars([
				'style'=> new Style()
			]);
	}
	
	function create() {
		Nn::authenticate();
		$style = new Style($_POST['name'],$_POST['value'],$_POST['description']);
		if($style->save()) {
			Utils::redirect(Nn::settings('DOMAIN').'/admin/styles');
		} else {
			die("failed to create setting");
		}
	}

	function edit($id=null) {
		Nn::authenticate();
		$this->setTemplateVars([
				'styles'=> Style::find_all()
			]);
		$this->setTemplateVars([
				'style'=> Style::find($id)
			]);
	}
	
	function update($id=null) {
		Nn::authenticate();
		$style = Style::find($id);
		$style = $style->fill($_POST['name'],$_POST['value'],$_POST['description']);
		if($style->save()) {
			Utils::redirect(Nn::settings('DOMAIN').'/admin/styles');
		} else {
			die("failed to update style");
		}
	}
	
	function delete($id=null) {
		Nn::authenticate();
		$style = Style::find($id);
		if($style->delete()) {
			Utils::redirect(Nn::settings('DOMAIN').'/admin/styles');
		}
	}
}

?>