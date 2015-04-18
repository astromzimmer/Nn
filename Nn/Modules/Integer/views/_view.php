<div id="integer<?php echo htmlentities($integer->attr('id')); ?>-content" class="integer">
	<?php if($integer->attributetype()->param('format') == 'timestamp'): ?>
		<?php echo Utils::formattedDate($integer->attr('number')); ?>
	<?php else: ?>
		<?php echo $integer->attr('number'); ?>
	<?php endif; ?>
</div>