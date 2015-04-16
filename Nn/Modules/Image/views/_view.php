<?php if($image->hasFile()): ?>
	<div class="image" data-id="<?php echo $image->attr('id') ?>"><?php echo $image->tag(); ?></div>
<?php else: ?>
	No image file found.
<?php endif; ?>