<?php if($image->hasFile()): ?>
	<div class="image" data-id="<?php echo $image->attr('id') ?>">
	<?php if($image->hasHref()): ?>
		<a href="<?php echo $image->attr('href') ?>"><?php echo $image->tag(); ?></a>
	<?php else: ?>
		<?php echo $image->tag(); ?>
	<?php endif; ?>
	</div>
<?php else: ?>
	<?php echo $image->attr('filename') ?> not found.
<?php endif; ?>