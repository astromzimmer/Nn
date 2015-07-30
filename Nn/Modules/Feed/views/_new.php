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
	<legend><?php echo Nn::babel('Since') ?></legend>
	<input type="date" name="since" class="formField" id="sinceField" value="" />
</fieldset>
<fieldset>
	<legend><?php echo Nn::babel('Until') ?></legend>
	<input type="date" name="until" class="formField" id="untilField" value="" />
</fieldset>