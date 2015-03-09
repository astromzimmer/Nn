<input type="hidden" name="MAX_FILE_SIZE" value="10000000" />
<input name="atype_id" type="hidden" value="<?php echo $attributetype->attr('id') ?>" />
<fieldset>
	<legend><?php echo Nn::babel('Title') ?></legend>
	<input name="title" type="text" class="formfield" id="titleField" value=""/>
</fieldset>
<fieldset>
	<legend><?php echo Nn::babel('Description') ?></legend>
	<textarea name="description" class="formfield" id="descriptionArea" rows="6"></textarea>
</fieldset>
<fieldset>
	<legend><?php echo Nn::babel('File') ?></legend>
	<input type="file" name="file_upload" />
</fieldset>