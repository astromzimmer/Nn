<?php if($document->hasFile()): ?>
	<div class="document" data-id="<?php echo $document->attr('id') ?>">
		<a href="<?php echo $document->publicPath() ?>"><?php echo $document->title(); ?></a>
		<div class="description"><?php echo $document->attr('description') ?></div>
	</div>
<?php else: ?>
	No document file found.
<?php endif; ?>