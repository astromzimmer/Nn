<?php if($image->hasFile()): ?>
	<div class="background image"
		data-id="<?php echo $image->attr('id') ?>"
		<?php if($image->hasHref()) echo 'data-href="'.$image->attr('href').'"' ?>
		style="
			position:absolute;
			top:0;
			left:0;
			right:0;
			bottom:0;
			background-image:url(<?php echo $image->src(1280) ?>);
			background-position:center;
			background-size:cover;
			background-repeat:no-repeat;
		"></div>
<?php else: ?>
	<?php echo $image->attr('filename') ?> not found.
<?php endif; ?>