<fieldset>
	<legend><?php echo Nn::babel('Title') ?></legend>
	<input name="title" type="text" class="formfield" id="titleField" value="<?php echo $image->attr('title') ?>" placeholder="<?php echo Nn::babel('Title') ?>" />
</fieldset>
<fieldset>
	<legend><?php echo Nn::babel('Description') ?></legend>
	<textarea name="description" class="formfield" id="descriptionArea" rows="6" placeholder="<?php echo Nn::babel('Description') ?>"><?php echo $image->attr('description') ?></textarea>
</fieldset>
<fieldset>
	<legend><?php echo Nn::babel('Link') ?></legend>
	<input name="href" type="text" class="formfield" id="hrefField" value="<?php echo $image->attr('href') ?>" placeholder="<?php echo Nn::babel('http://...') ?>" />
</fieldset>
<fieldset>
	<legend><?php echo Nn::babel('File') ?></legend>
	<input type="file" name="file_upload" />
</fieldset>