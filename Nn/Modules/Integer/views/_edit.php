<?php if($attributetype->param('format') == 'timestamp'): ?>
<div class="note">
	Unless you see a date picker, enter date according to<br>
	%Y-%m-%d<br>
	<br>
	This will yield<br>
	DATE: <?php echo Nn::s('DATE_FORMAT') ?><br>
	TIME: <?php echo Nn::s('TIME_FORMAT') ?><br>
	DATE & TIME: <?php echo Nn::s('DATETIME_FORMAT') ?><br><br>
</div>
<input type="date" name="number" class="formField" id="numberField" value="<?php echo strftime('%Y-%m-%d',$integer->attr('number')) ?>" autofocus />
<?php else: ?>
<input name="number" type="number" value="<?php echo $integer->attr('number') ?>" class="formfield" id="numberField" autofocus />
<?php endif; ?>