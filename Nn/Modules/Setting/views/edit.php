<div id="left">
	<?php Nn::partial('setting','_list',array('settings'=>$settings,'setting'=>$setting)) ?>
</div>
<div id="right">
	<div class="manage">
		<div id="setting_form" class="edit_bg">
			<form name="form1" method="post" action="<?php echo DOMAIN,DS,'admin',DS,'settings',DS,'update',DS,$setting->attr('id') ?>">
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
			    <button type="submit" name="submit" id="submit">submit</button>
			    <button type="delete" name="delete" id="delete">delete</button>
			  </div>
			</form>
			<div class="tools">
				<a class="trash" href="<?php echo DOMAIN.DS.'admin'.DS.'settings'.DS.'delete'.DS.$setting->attr('id') ?>" data-tooltip="<?php echo Nn::babel('Trash') ?>"><?php Utils::UIIcon('trash'); ?></a>
			</div>
		</div>
	</div>
</div>