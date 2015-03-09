<div class="note">
	Unless you see a date picker, enter date according to<br>
	%Y-%m-%d<br>
	<br>
	This will yield<br>
	DATE: <?php echo DATE_FORMAT ?><br>
	TIME: <?php echo TIME_FORMAT ?><br>
	DATE & TIME: <?php echo DATETIME_FORMAT ?><br><br>
</div>
<input name="atype_id" type="hidden" value="<?php echo $attributetype->attr('id') ?>" />
<input type="date" name="timestamp" class="formField" id="timestampField" value="<?php echo strftime('%Y-%m-%d',time()) ?>" />