<div id="login" class="centered">
	<div id="login_form">
	<?php if(isset($logo)): ?>
		<div class="loginLogo">
			<?php echo partial($logo->public_view(),array(strtolower($logo->datatype())=>$logo->data())) ?>
		</div>
	<?php endif; ?>
		<form name="form1" method="post" action="<?php echo DOMAIN,DS,'admin',DS,'try_to_login' ?>" enctype="multipart/form-data">
		  <fieldset>
			<legend><?php echo Nn::babel('Email') ?></legend>
		    <input name="uid" type="text" class="formfield" id="emailField" value="" autofocus />
		  </fieldset>
		  <fieldset>
		  	<legend><?php echo Nn::babel('Password') ?></legend>
		    <input name="pwd" type="password" class="formfield" id="pwField" value="" />
		  </fieldset>
		  <div class="submit">
		    <button type="submit" name="submit" id="submit"><?php echo Nn::babel('Log in') ?></button>  
		  </div>
		</form>
		<a href="/admin/forgot"><?php echo Nn::babel('Forgot password') ?>?</a>
	</div>
</div>