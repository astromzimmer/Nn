<fieldset>
	<legend><?php echo Nn::babel('Title') ?></legend>
	<input name="title" type="text" class="formfield" id="titleField" value="<?php echo $image->attr('title') ?>" placeholder="<?php echo Nn::babel('Title') ?>" />
</fieldset>
<fieldset>
	<legend><?php echo Nn::babel('Description') ?></legend>
	<textarea name="description" class="formfield md" id="descriptionArea" rows="6" placeholder="<?php echo Nn::babel('Description') ?>"><?php echo $image->attr('description') ?></textarea>
</fieldset>