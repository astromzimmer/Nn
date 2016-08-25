<?php

namespace Nn\Core;
use Nn;

class Mailer extends \PHPMailer {
	
	private $priority = 3;
	public $to_name;
	public $to_email;
	// set to admin email by default. use a named {from} address to avoid spam filters; First Last <mail@domain.topdomain>
	public $From;
	public $FromName;
	public $Sender;
	
	public function setSender($from_email=null, $from_name=null) {
		$this->From = $from_email;
		$this->FromName = $from_name;
		$this->Sender = $from_email;
	}
	
	public function __construct() {
		parent::__construct(true);
		$this->CharSet = 'UTF-8';
		if(Nn::settings('MAIL')['SMTP_MODE']) {
			$this->IsSMTP();
			$this->Host = Nn::settings('MAIL')['SMTP_HOST'];
			if(Nn::settings('MAIL')['SMTP_USERNAME']) {
				$this->SMTPAuth = true;
				$this->Username = Nn::settings('MAIL')['SMTP_USERNAME'];
				$this->Password = Nn::settings('MAIL')['SMTP_PASSWORD'];
				if(Nn::settings('MAIL')['SMTP_PROTOCOL']) $this->SMTPSecure = Nn::settings('MAIL')['SMTP_PROTOCOL'];
				if(Nn::settings('MAIL')['SMTP_PORT']) $this->Port = Nn::settings('MAIL')['SMTP_PORT'];
			}
		}
		if(!isset($this->From)) {
			$this->From = Nn::settings('MAIL')['FROM_EMAIL'];
		}
		if(!isset($this->FromName)) {
			$this->FromName = Nn::settings('MAIL')['FROM_NAME'];
		}
		if(!isset($this->Sender)) {
			$this->Sender = Nn::settings('MAIL')['FROM_EMAIL'];
		}
		$this->Priority = $this->priority;
	}
}

?>