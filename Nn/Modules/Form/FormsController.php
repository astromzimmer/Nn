<?php

namespace Nn\Modules\Form;
use Nn\Modules\Attribute\Attribute as Attribute;
use Nn;
use Utils;

class FormsController extends Nn\Core\Controller {

	function before() {
		
	}

	function index() {
		Nn::authenticate();
		$this->setTemplateVars([
				'forms'=> Form::find_all()
			]);
	}

	function view($id=null) {
		Nn::authenticate();
		$forms = Form::find_all();
		$form = Form::find($id);
		$entries = ($form) ? $form->entries() : [];
		$this->setTemplateVars([
				'forms'=> $forms,
				'form'=> $form,
				'entries'=> $entries
			]);
	}

	function download($id,$all=null) {
		Nn::authenticate();
		$form = Form::find($id);
		if(isset($all)) {
			$zip_path = $form->zip(true);
		} else {
			$zip_path = $form->zip();
		}
		$this->renderMode('binary');
		$this->setTemplateVars([
				'data'=>$zip_path
			]);
	}

	function attachment($entry_id=null,$filename=null) {
		Nn::authenticate();
		if(isset($entry_id) && isset($filename)) {
			$filename = urldecode($filename);
			$path = Entry::uploadDir().DS.$entry_id.DS.$filename;
			if(file_exists($path)) {
				$this->renderMode('binary');
				$this->setTemplateVars([
						'data'=>$path
					]);
			} else {
				$this->renderMode('json');
				$return = ['error'=>'No such file'];
				Utils::sendResponseCode(404);
				$json_data = json_encode($return);
				$this->setTemplateVars(['data'=>$json_data]);
			}
		}
	}

	function create() {
		Nn::authenticate();
		$node_id = $_POST['node_id'];
		$form = new Form($_POST['name'], $_POST['mailto'],$_POST['content'],$_POST['aMD_markup']);
		if($form->save()) {
			$attribute = new Attribute($node_id,$_POST['atype_id'],$form->attr('id'));
			if($attribute->save()) {
				Utils::redirect(Nn::s('DOMAIN').'/admin/nodes/'.Nn::settings('NODE_VIEW').'/'.$node_id);
			} else {
				$form->delete();
				Nn::flash(['error'=>Nn::babel("Failed to register attribute")]);
				Utils::redirect(Nn::referer());
			}
		} else {
			Nn::flash(['error'=>Nn::babel("Failed to create form")]);
			Utils::redirect(Nn::referer());
		}
	}
	
	function update($id=null) {
		Nn::authenticate();
		$form = Form::find($id);
		$form = $form->fill($_POST['name'], $_POST['mailto'], $_POST['content'],$_POST['aMD_markup']);
		$node_id = $_POST['node_id'];
		if($form->save()) {
			# Attribute save needs error handling - yawn
			$attribute = $form->attribute();
			$attributetype_id = $_POST['attributetype_id'];
			$attribute->attr('attributetype_id',$attributetype_id);
			$attribute->save();
			Utils::redirect(Nn::s('DOMAIN').'/admin/nodes/'.Nn::settings('NODE_VIEW').'/'.$node_id);
		} else {
			die("failed to update form");
		}
	}

	function submit($id=null) {
		$this->renderMode('json');
		try {
			if($_POST && isset($id)) {
				$form = Form::find($id);
				$entry = new Entry($id);
				$all_good = $entry->save();
				$all_good = $entry->data($_POST);
				if($_FILES && is_array($_FILES) && !empty($_FILES)) {
					$all_good = $entry->files($_FILES);
				} else {
					$return = ['error'=>'No data found. Is the form empty?'];
					Utils::sendResponseCode(500);
				}
				if($all_good) {
					$entry->save();
					$email = $_POST['email'];
					$form->send($email);
					$form->sendThanks($email);
					$return = ['success'=>'Thank you very much for your application for Sound Development City 2016.<br>A confirmation E-mail has been sent to '.$email.'.'];
				} else {
					$return = ['error'=>$entry->errors()];
					$entry->delete();
					Utils::sendResponseCode(500);
				}
			} else {
				$return = ['error'=>'No data found. Is the form empty?'];
				Utils::sendResponseCode(500);
			}
		} catch(Exception $e) {
			$return = ['error'=>'Something went wrong.'];
			Utils::sendResponseCode(500);
		}
		$json_data = json_encode($return);
		$this->setTemplateVars(['data'=>$json_data]);
	}

	function delete($entry_id=null) {
		Nn::authenticate();
		$entry = Entry::find($entry_id);
		$form_id = $entry->attr('form_id');
		if($entry->delete()) {
			Nn::flash(['success'=>Nn::babel('Attribute deleted successfully')]);
		} else {
			Nn::flash(['error'=>Nn::babel('Error! Please contact site admin')]);
		}
		Utils::redirect(Nn::s('DOMAIN').'/admin/forms/view/'.$form_id);
	}

}