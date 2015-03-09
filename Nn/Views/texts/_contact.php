<div id="text<?php echo htmlentities($text->attr('id')); ?>-content" class="contact">
	<?php echo (Utils::mailto($text->attr('content'))) ?>
</div>