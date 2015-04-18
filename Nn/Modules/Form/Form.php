<?php

namespace Nn\Modules\Form;
use Nn;
use Nn\Core\Mailer as Mailer;

class Form extends Nn\Modules\Text\Text {

	protected $name;
	protected $mailto;
	
	public static $SCHEMA = array(
			'attribute_id' => 'integer',
			'name' => 'short_text',
			'mailto' => 'text',
			'content' => 'long_text',
			'markup' => 'long_text',
			'created_at' => 'integer',
			'updated_at' => 'integer'
		);

	public static $PARAMS = array();

	
	public function mailto() {
		return $this->mailto;
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
	
	public function send() {
		if($_POST['spmchk'] == "hmn") {

			if(isset($_POST['email'])) {
				$email = " by {$_POST['email']}";
			} else {
				$email = "";
			}

			$referer = Nn::referer();
			
			unset($_POST['spmchk']);
			unset($_POST['submit']);
			
			// CREATE PLAIN TEXT MESSAGE
			$plainTextMessage = "The following message was submitted".$email.", via the form on ".$referer.":\r\n\r\n";
			
			foreach($_POST as $key=>$val) {
				$plainTextMessage .= $key.":\r\n";
				$plainTextMessage .= $val."\r\n\r\n";
			}
			
			// CREATE HTML MESSAGE
			$HTMLMessage = "<html>
								<head>
									<title>
										Contact form on ".PAGE_NAME.": {$this->name}
									</title>
								</head>
								<body>
									<p style=\"font-size:24px;\">
										{$this->name}
									</p>
									<p>
										The following message was submitted{$email},<br/>via the form on {$referer}:
									</p>
									<br/>";
			foreach($_POST as $key=>$val) {
				$HTMLMessage .= 	"<p><strong>{$key}:</strong><br/>{$val}</p>";
			}								
			$HTMLMessage .=		"</body>
							</html>";
			
			$mailer = new Mailer();
		
			$mailer->Subject = "Contact form on ".PAGE_NAME.": {$this->name}";
			$mailer->Body = $HTMLMessage;
			$mailer->isHTML = true;
			$mailer->AltBody = $plainTextMessage;
			
			$mailer->AddAddress($this->mailto);
			
			if(!$mailer->Send()) {
				$result = false;
			} else {
				$result = true;
			}
			$mailer->ClearAddresses();
			$mailer->ClearAttachments();

			return $result;

		} else {			
			die('Bot!');
		}
	}
}

?>