<div id="left">
	<?php Nn::partial('User','_tree',array('roles'=>$roles,'focus'=>$user,'role'=>$role)) ?>
</div>
<div id="center">
	<div class="manage">
		<div id="user_form" class="view">
		<?php if($user->attr('id')) : ?>
		<div id="user_form" class="view">
			<form name="form1" method="post" action="<?php echo Nn::settings('DOMAIN').'/admin/users/update/'.$user->attr('id'); ?>" enctype="multipart/form-data">
			  <fieldset>
				<legend><?php echo Nn::babel('First name') ?></legend>
			    <input name="first_name" type="text" class="formfield" id="FNField" value="<?php echo $user->attr('first_name') ?>" />
			  </fieldset>
			  <fieldset>
				<legend><?php echo Nn::babel('Last name') ?></legend>
			    <input name="last_name" type="text" class="formfield" id="LNField" value="<?php echo $user->attr('last_name') ?>" />
			  </fieldset>
			  <fieldset>
				<legend><?php echo Nn::babel('Email') ?></legend>
			    <input name="uid" type="text" class="formfield" id="UIDField" value="<?php echo $user->attr('email') ?>" />
			  </fieldset>
			  <fieldset>
				<legend><?php echo Nn::babel('Password') ?></legend>
			    <input name="pwd" type="password" class="formfield" id="PWDField" value="" />
			  </fieldset>
		<?php else : ?>
			<form name="form1" method="post" action="<?php echo Nn::settings('DOMAIN').'/admin/users/create'; ?>" enctype="multipart/form-data">
			  <input name="role_id" type="hidden" class="formfield" id="RIDField" value="<?php echo $role->attr('id') ?>" />
			  <fieldset>
				<legend><?php echo Nn::babel('First name') ?></legend>
			    <input name="first_name" type="text" class="formfield" id="FNField" value="" />
			  </fieldset>
			  <fieldset>
				<legend><?php echo Nn::babel('Last name') ?></legend>
			    <input name="last_name" type="text" class="formfield" id="LNField" value="" />
			  </fieldset>
			  <fieldset>
				<legend><?php echo Nn::babel('Email') ?></legend>
			    <input name="uid" type="text" class="formfield" id="UIDField" value="" />
			  </fieldset>
			  <fieldset>
				<legend><?php echo Nn::babel('Password') ?></legend>
			    <input name="pwd" type="password" class="formfield" id="PWDField" value="" />
			  </fieldset>
		<?php endif; ?>
			  <div class="submit">
			    <button type="submit" name="submit" id="submit"><?php echo Nn::babel('Save') ?></button>  
			  </div>
			</form>
		</div>
	</div>
</div>