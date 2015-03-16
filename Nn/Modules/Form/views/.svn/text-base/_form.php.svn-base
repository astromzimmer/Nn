<?php if(isset($_POST['spmchk'])): ?>
	
<?php
	if($_POST['spmchk'] == "hmn") {
		$name = 'me_bot';
		if(isset($_POST['email'])) {
			$email = " by {$_POST['email']}";
		} else {
			$email = "";
		}
		
		unset($_POST['spmchk']);
		unset($_POST['submit']);
		
		// CREATE PLAIN TEXT MESSAGE
		$plainTextMessage = "The following message was submitted".$email.", via the form on ".$url.":\r\n\r\n";
		
		foreach($_POST as $key=>$val) {
			$plainTextMessage .= $key.":\r\n";
			$plainTextMessage .= $val."\r\n\r\n";
		}
		
		// CREATE HTML MESSAGE
		$HTMLMessage = "<html>
							<head>
								<title>
									Contact form on ".DOMAIN.": {$contactform->title}
								</title>
							</head>
							<body>
								<p style=\"font-size:24px;\">
									{$contactform->title}
								</p>
								<p>
									The following message was submitted{$email},<br/>via the form on {$url}:
								</p>
								<br/>
								<p>";
		foreach($_POST as $key=>$val) {
			$HTMLMessage .= "<p><strong>{$key}:</strong><br/>{$val}</p>";
		}								
		$HTMLMessage .=			"</p>
							</body>
						</html>";
		
		$mailer = new Mailer();
		
		$mailer->Subject = "Contact form on ".DOMAIN.": {$contactform->title}";
		$mailer->Body = $HTMLMessage;
		$mailer->isHTML = true;
		$mailer->AltBody = $plainTextMessage;
		
		$mailer->AddAddress($contactform->mailto);
//		$mailer->AddAddress('atelier@lukaszimmer.com');
		
		if(!$mailer->Send()) {
			echo '<p style="color:#C00;">Sorry!</p>Something went wrong.<br/>Please contact site administrator, or try again later.';
		} else {
			echo '<p style="color:#0C0;">Thank you!</p>Your message has been sent.';
		}
		$mailer->ClearAddresses();
		$mailer->ClearAttachments();
		die();
		
		// END OF THE SENDING MAIL STUFF
		
	} else {
		$result = "You're a bot.";
		echo $result;
		die();
	}

?>

<?php endif; ?>

<div id="form<?php echo htmlentities($contactform->id); ?>-content" class="contactform">
	<form id="contact_form" name="contact_form" method="post" action="" enctype="multipart/form-data">
		<input name="spmchk" type="text" id="spmchk" value="hmn" style="display: none;" />
		<ul>
			<?php echo htmlspecialchars_decode($contactform->content()); ?>
		<li>
			<button type="submit" name="submit" id="submit">submit</button>
		</li>
		</ul>
	</form>
</div>