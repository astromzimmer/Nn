<input name="atype_id" type="hidden" value="<?php echo $attributetype->attr('id') ?>" />
<?php if($attributetype->param('format') == 'timestamp'): ?>
<div class="note">
	Unless you see a date picker, enter date according to<br>
	%Y-%m-%d<br>
	<br>
	This will yield<br>
	DATE: <?php echo DATE_FORMAT ?><br>
	TIME: <?php echo TIME_FORMAT ?><br>
	DATE & TIME: <?php echo DATETIME_FORMAT ?><br><br>
</div>
<input type="date" name="number" class="formField" id="numberField" value="" />
<?php else: ?>
<input name="number" type="number" value="" class="formfield" placeholder="<?php echo Nn::babel('Enter an integer') ?>" id="numberField" />
<?php endif; ?>