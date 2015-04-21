<?php if($image->hasFile()): ?>
	<div class="image" data-id="<?php echo $image->attr('id') ?>">
	<?php if($image->hasHref()): ?>
		<a href="<?php echo $image->attr('href') ?>"><?php echo $image->tag(920); ?></a>
	<?php else: ?>
		<?php echo $image->tag(920); ?>
	<?php endif; ?>
	</div>
<?php else: ?>
	No image file found.
<?php endif; ?>