<fieldset>
	<legend><?php echo Nn::babel('Handle / Username') ?></legend>
	<input name="handle" class="formField" id="handleField" value="<?php echo $feed->attr('handle') ?>" />
</fieldset>
<fieldset>
	<legend><?php echo Nn::babel('#hashtag') ?></legend>
	<input name="hashtag" class="formField" id="hashField" value="<?php echo $feed->attr('hashtag') ?>" />
</fieldset>
<fieldset>
	<legend><?php echo Nn::babel('Timeout') ?></legend>
	<input name="timeout" class="formField" id="timeoutField" value="<?php echo $feed->attr('timeout') ?>" placeholder="Enter timeout in sec" />
</fieldset>