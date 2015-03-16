<div class="image" data-id="<?php echo $image->node()->attr('id') ?>">
<?php $size = $image->size(); ?>
<img alt="<?php echo $image->title() ?>"
	src="<?php echo $image->src(280,false,true) ?>"
	data-bw_thumb_src="<?php echo $image->src(280,false,true) ?>"
	data-thumb_src="<?php echo $image->src(280) ?>"
	data-full_src="<?php echo $image->src() ?>"
	width="<?php $size[0] ?>"
	height="<?php $size[1] ?>" /></div>