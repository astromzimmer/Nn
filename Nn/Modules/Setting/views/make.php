<div id="left">
	<?php Nn::partial('Setting','_list',array('settings'=>$settings)) ?>
</div>
<div id="center">
	<div class="manage">
		<div id="setting_form" class="edit_bg">
			<form name="form1" method="post" action="<?php echo Nn::settings('DOMAIN'),'/admin/settings/create' ?>">
			  <fieldset>
				<legend><?php echo Nn::babel('Name') ?></legend>
			    <input type="text" name="name" class="formfield" id="nameField" value="" />
			  </fieldset>
			  <fieldset>
				<legend><?php echo Nn::babel('Value') ?></legend>
			    <input type="text" name="value" class="formfield" id="valueField" value="" />
			  </fieldset>
			  <fieldset>
				<legend><?php echo Nn::babel('Description') ?></legend>
			    <textarea name="description" class="formfield" id="descriptionField" placeholder="<?php echo Nn::babel('Type some notes regarding this setting and its options...') ?>"></textarea>
			  </fieldset>
			  <div class="submit">
			    <button type="submit" name="submit" id="submit"><?php echo Nn::babel('Save') ?></button>  
			  </div>
			</form>
		</div>
	</div>
</div>