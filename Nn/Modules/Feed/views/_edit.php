<fieldset>
	<legend><?php echo Nn::babel('Handle / Username') ?></legend>
	<input name="handle" class="formField" id="handleField" value="<?php echo $feed->attr('handle') ?>" />
</fieldset>
<fieldset>
	<legend><?php echo Nn::babel('#hashtag') ?></legend>
	<input name="hashtag" class="formField" id="hashField" value="<?php echo $feed->attr('hashtag') ?>" />
</fieldset>
<fieldset>
	<legend><?php echo Nn::babel('Since') ?></legend>
	<input type="date" name="since" class="formField" id="sinceField" value="<?php echo strftime('%Y-%m-%d',$feed->attr('since')) ?>" />
</fieldset>
<fieldset>
	<legend><?php echo Nn::babel('Until') ?></legend>
	<input type="date" name="until" class="formField" id="untilField" value="<?php echo strftime('%Y-%m-%d',$feed->attr('until')) ?>" />
</fieldset>