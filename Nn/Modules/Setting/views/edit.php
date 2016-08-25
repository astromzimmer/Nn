<div id="left">
	<?php Nn::partial('Setting','_list',array('settings'=>$settings,'setting'=>$setting)) ?>
</div>
<div id="center">
	<div class="manage">
		<div id="setting_form" class="edit_bg">
			<form name="form1" method="post" action="<?php echo Nn::settings('DOMAIN'),'/admin/settings/update/',$setting->attr('id') ?>">
			  <fieldset>
				<legend><?php echo Nn::babel('Name') ?></legend>
			    <input type="text" name="name" class="formfield" id="nameField" value="<?php echo $setting->attr('name'); ?>" />
			  </fieldset>
			  <fieldset>
				<legend><?php echo Nn::babel('Value') ?></legend>
			    <input type="text" name="value" class="formfield" id="valueField" value="<?php echo $setting->attr('value'); ?>" />
			  </fieldset>
			  <fieldset>
				<legend><?php echo Nn::babel('Description') ?></legend>
			    <textarea name="description" class="formfield" id="descriptionField" placeholder="<?php echo Nn::babel('Type some notes regarding this setting and its options...') ?>"><?php echo $setting->description(); ?></textarea>
			  </fieldset>
			  <div class="submit">
			    <a href="<?php echo Nn::settings('DOMAIN'),'/admin/settings/delete/',$setting->attr('id') ?>" class="delete button half float"><?php echo Nn::babel('Delete') ?></a>
			    <button type="submit" name="submit" id="submit" class="half float"><?php echo Nn::babel('Save') ?></button>
			  </div>
			</form>
		</div>
	</div>
</div>