<?php if($image->hasFile()): ?>
	<?php $size = $image->size(); ?>
	<div class="image" data-id="<?php echo $image->attr('id') ?>"><?php echo $image->tag(); ?></div>
<?php else: ?>
	No image file found.
<?php endif; ?>