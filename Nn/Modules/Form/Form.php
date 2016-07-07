<?php

namespace Nn\Modules\Form;
use Nn;
use Utils;
use Nn\Core\Mailer as Mailer;
use ZipArchive;

class Form extends Nn\Modules\Text\Text {

	protected $name;
	protected $mailto;
	
	public static $SCHEMA = array(
			'name' => 'short_text',
			'mailto' => 'text',
			'content' => 'long_text',
			'markup' => 'long_text',
			'created_at' => 'float',
			'updated_at' => 'float'
		);

	public static $PARAMS = array();

	public static $DEFAULT = false;

	
	public function mailto() {
		return $this->mailto;
	}

	private function exportDir() {
		return ROOT.DS.'form_data'.DS.'exports';
	}

	public function cleanName() {
		return str_replace(' ', '_', $this->name);
	}
	
	public function __construct($name=null,$mailto=null,$content=null,$markup=null){
		if(!empty($content)){
			$this->name = $name;
			$this->mailto = $mailto;
			$this->content = $content;
			$this->markup = htmlspecialchars(str_replace("\n", "", $markup));
			return $this;
		} else {
			return false;
		}
	}
	
	public function fill($name=null,$mailto=null, $content=null, $markup=null){
		if(!empty($content)){
			$this->name = $name;
			$this->mailto = $mailto;
			$this->content = addslashes(str_replace("\n", "", $content));
			$this->markup = htmlspecialchars(str_replace("\n", "", $markup));
			return $this;
		} else {
			return false;
		}
	}

	public function entries() {
		$entries = Entry::find(['form_id'=>$this->id],null,'-created_at');
		return $entries;
	}

	public function csv() {
		$entries = $this->entries();
		if($entries) {
			$dir = $this->exportDir();
			if(!file_exists($dir)) {
				mkdir($dir,0777,true);
			}
			$path = $dir.DS.'entries.csv';
			$csv = fopen($path,'w');
			$has_set_headers = false;
			foreach($entries as $entry) {
				$csv_array = [];
				$data = $entry->data();
				if(!$has_set_headers) {
					array_push($csv_array, 'ENTRY ID');
					foreach($data as $name => $value) {
						array_push($csv_array, strtoupper($name));
					}
					fputcsv($csv, $csv_array);
					$csv_array = [];
					$has_set_headers = true;
				}
				array_push($csv_array, $entry->attr('id'));
				foreach($data as $name => $value) {
					$value = preg_replace('/[\r\n]+/', " ", trim($value));
					$value = mb_convert_encoding($value, 'UTF-16LE', 'UTF-8');
					array_push($csv_array, $value);
				}
				fputcsv($csv, $csv_array);
			}
			# Convert encoding for proper Excel display
			fclose($csv);
			return $path;
		} else {
			return false;
		}
	}

	public function zip($include_files=false) {
		$to_unlink = [];
		$path = $this->exportDir().DS.$this->cleanName().'-'.$this->id.'.zip';
		if(file_exists($path)) {
			unlink($path);
		}
		$zip = new ZipArchive();
		$zip->open($path,ZipArchive::CREATE);
		$entries = $this->entries();
		foreach($entries as $entry) {
			$csv_path = $this->csv();
			$zip->addFile($csv_path,'entries.csv');
			array_push($to_unlink, $csv_path);
			if($include_files && $files = $entry->files()) {
				foreach($files as $file) {
					$zip->addFile($file['path'],'attachments'.DS.$entry->attr('id').DS.$file['name']);
				}
			}
		}
		$zip->close();
		foreach($to_unlink as $file) {
			if(file_exists($file)) {
				unlink($file);
			}
		}
		return $path;
	}
	
	public function send($email=null) {

		if(isset($email)) {
			$email_str = " by {$email}";
		} else {
			$email_str = "";
		}

		$referer = Nn::referer();
		
		unset($_POST['spmchk']);
		unset($_POST['MAX_FILE_SIZE']);
		unset($_POST['submit']);
		
		// CREATE PLAIN TEXT MESSAGE
		$plainTextMessage = "The following message was submitted{$email_str}, via the {$this->name} form on {$referer}:\r\n\r\n";
		
		foreach($_POST as $key=>$val) {
			$plainTextMessage .= $key.":\r\n";
			$plainTextMessage .= $val."\r\n\r\n";
		}
		
		$domain = Nn::settings('DOMAIN');

		// CREATE HTML MESSAGE
		$HTMLMessage = "<html>
							<head>
								<title>
									{$this->name} form on {$domain}: {$this->name}
								</title>
							</head>
							<body>
								<p style=\"font-size:24px;\">
									{$this->name}
								</p>
								<p>
									The following message was submitted{$email_str},<br/>via the form on {$referer}:
								</p>
								<br/>";
		foreach($_POST as $key=>$val) {
			$HTMLMessage .= 	"<p><strong>{$key}:</strong><br/>{$val}</p>";
		}								
		$HTMLMessage .=		"</body>
						</html>";
		
		$mailer = new Mailer();
	
		$mailer->Subject = "{$this->name} form on {$domain}";
		$mailer->Body = $HTMLMessage;
		$mailer->isHTML = true;
		$mailer->AltBody = $plainTextMessage;
		
		$mailer->AddAddress($this->mailto);
		
		try {
			$mailer->Send();
			$result = true;
		} catch(phpmailerException $e) {
			trigger_error($e);
			$result = false;
		} catch(Exception $e) {
			trigger_error($e);
			$result = false;
		}
		$mailer->ClearAddresses();
		$mailer->ClearAttachments();

		return $result;
	}

	public function sendThanks($email=null) {
		
		// CREATE PLAIN TEXT MESSAGE
		$plainTextMessage = "Thank you very much for your application for Sound Development City 2016.\r\n
			The application process is open until April 11th. The outcome of the jury meeting will be announced by the end of May 2016.\r\n
			Please get in touch with any questions: info@sound-development-city.com\r\n\r\n
			You have submitted the following information:\r\n\r\n";
		
		foreach($_POST as $key=>$val) {
			$plainTextMessage .= $key.":\r\n";
			$plainTextMessage .= $val."\r\n\r\n";
		}
		
		$domain = Nn::settings('DOMAIN');

		// CREATE HTML MESSAGE
		$HTMLMessage = "<html>
							<head>
								<title>
									{$this->name} form on {$domain}: {$this->name}
								</title>
							</head>
							<body>
								<p style=\"font-size:24px;\">
									{$this->name}
								</p>
								<p>
									Thank you very much for your application for Sound Development City 2016.<br>
									The application process is open until April 11th. The outcome of the jury meeting will be announced by the end of May 2016.<br>
									Please get in touch with any questions: <a href=\"mailto:info@sound-development-city.com\">info@sound-development-city.com</a>
								</p>
								<p>
									You have submitted the following information:
								</p>
								<br/>";
		foreach($_POST as $key=>$val) {
			$HTMLMessage .= 	"<p><strong>{$key}:</strong><br/>{$val}</p>";
		}								
		$HTMLMessage .=		"</body>
						</html>";
		
		$mailer = new Mailer();
	
		$mailer->Subject = "{$this->name} form on {$domain}";
		$mailer->Body = $HTMLMessage;
		$mailer->isHTML = true;
		$mailer->AltBody = $plainTextMessage;
		
		$mailer->AddAddress($email);
		
		try {
			$mailer->Send();
			$result = true;
		} catch(phpmailerException $e) {
			trigger_error($e);
			$result = false;
		} catch(Exception $e) {
			trigger_error($e);
			$result = false;
		}
		$mailer->ClearAddresses();
		$mailer->ClearAttachments();

		return $result;
	}
}

?>