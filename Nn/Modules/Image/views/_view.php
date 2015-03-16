<?php if($image->hasFile()): ?>
	<?php $size = $image->size(); ?>
	<div class="image" data-id="<?php echo $image->attr('id') ?>"><img alt="<?php echo $image->title() ?>"
	src="<?php echo $image->src() ?>"
	data-bw_thumb_src="<?php echo $image->src(220,false,true) ?>"
	data-thumb_src="<?php echo $image->src(220) ?>"
	data-full_src="<?php echo $image->src() ?>"
	width="<?php $size[0] ?>"
	height="<?php $size[1] ?>" /></div>
<?php else: ?>
	No image file found.
<?php endif; ?>