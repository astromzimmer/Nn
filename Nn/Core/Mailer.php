<?php

namespace Nn\Core;
use Nn;

require_once ROOT.DS.'vendor'.DS.'PHPMailer'.DS.'PHPMailerAutoload.php';

class Mailer extends \PHPMailer {
	
	private $priority = 3;
	public $to_name;
	public $to_email;
	// set to admin email by default. use a named {from} address to avoid spam filters; First Last <mail@domain.topdomain>
	public $From = null;
	public $FromName = null;
	public $Sender = null;
	
	public function setSender($from_email, $from_name) {
		$this->From = $from_email;
		$this->FromName = $from_name;
		$this->Sender = $from_email;
	}
	
	public function __construct() {
		parent::__construct();
		if(Nn::settings('SMTP_MODE')) {
			$this->IsSMTP();
			$this->Host = Nn::settings('SMTP_HOST');
			$this->Port = Nn::settings('SMTP_PORT');
			if(Nn::settings('SMTP_USERNAME')) {
				$this->SMTPAuth = true;
				$this->Username = Nn::settings('SMTP_USERNAME');
				$this->Password = Nn::settings('SMTP_PASSWORD');
			}
		}
		if(!$this->From) {
			$this->From = Nn::settings('FROM_EMAIL');
		}
		if(!$this->FromName) {
			$this->FromName = Nn::settings('FROM_NAME');
		}
		if(!$this->Sender) {
			$this->Sender = Nn::settings('FROM_EMAIL');
		}
		$this->Priority = $this->priority;
	}
}

?>