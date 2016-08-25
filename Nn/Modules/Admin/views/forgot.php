<div id="login" class="centered">
	<div id="forgot_form">
	<?php if(isset($logo)): ?>
		<div class="loginLogo">
			<?php echo partial($logo->public_view(),array(strtolower($logo->datatype())=>$logo->data())) ?>
		</div>
	<?php endif; ?>
		<form name="form1" method="post" action="<?php echo Nn::s('DOMAIN'),'admin/reset_password' ?>" enctype="multipart/form-data">
		  <fieldset>
			<legend><?php echo Nn::babel('Email') ?></legend>
		    <input name="uid" type="text" class="formfield" id="emailField" value="" autofocus />
		  </fieldset>
		  <div class="submit">
		    <button type="submit" name="submit" id="submit"><?php echo Nn::babel('Reset password') ?></button>  
		  </div>
		</form>
	</div>
</div>