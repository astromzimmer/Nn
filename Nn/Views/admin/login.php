<div id="login" class="centered">
	<div id="login_form">
	<?php if(isset($logo)): ?>
		<div class="loginLogo">
			<?php echo partial($logo->public_view(),array(strtolower($logo->datatype())=>$logo->data())) ?>
		</div>
	<?php endif; ?>
		<form name="form1" method="post" action="<?php echo DOMAIN,DS,'admin',DS,'try_to_login' ?>" enctype="multipart/form-data">
		  <fieldset>
			<legend><?php echo Nn::babel('Username') ?></legend>
		    <input name="uid" type="text" class="formfield" id="nameField" value="" autofocus />
		  </fieldset>
		  <fieldset>
		  	<legend><?php echo Nn::babel('Password') ?></legend>
		    <input name="pwd" type="password" class="formfield" id="nameField" value="" />
		  </fieldset>
		  <div class="submit">
		    <button type="submit" name="submit" id="submit"><?php echo Nn::babel('Log in') ?></button>  
		  </div>
		</form>
	</div>
</div>