<?php if($default): ?>
  	<fieldset>
		<legend><?php echo Nn::babel('Default') ?></legend>
		<?php if($default == 'textarea'): ?>
			<textarea type="text" name="default_value" /></textarea>
		<?php endif ?>
	</fieldset>
<?php endif ?>