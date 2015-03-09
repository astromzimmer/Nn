<div id="feed<?php echo htmlentities($feed->attr('id')); ?>-content" class="feed">
	<?php echo $feed->attr('handle'); ?>
	<a href="<?php echo DOMAIN,'/admin/feeds/fetch/',$feed->attr('id') ?>">Fetch #<?php echo $feed->attr('hashtag') ?></a>
</div>