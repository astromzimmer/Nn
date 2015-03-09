<input name="atype_id" type="hidden" value="<?php echo $attributetype->attr('id') ?>" />
<fieldset>
	<legend><?php echo Nn::babel('Handle / Username') ?></legend>
	<input name="handle" class="formField" id="feedField" value="" />
</fieldset>
<fieldset>
	<legend><?php echo Nn::babel('#hashtag') ?></legend>
	<input name="hashtag" class="formField" id="hashField" value="" />
</fieldset>
<fieldset>
	<legend><?php echo Nn::babel('Timeout') ?></legend>
	<input name="timeout" class="formField" id="hashField" value="" placeholder="Enter timeout in sec" />
</fieldset>