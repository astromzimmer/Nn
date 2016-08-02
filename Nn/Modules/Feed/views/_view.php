<div id="feed<?php echo htmlentities($feed->attr('id')); ?>-content" class="feed">
	<div class="title"><?php echo $feed->attr('handle'); ?></div>
	<?php if ($feed->attr('auth') == 'AUTH'): ?>
	<div class="note">
		Please have the user authorise this page for access.
	</div>
	<div class="tools">
		<?php $feed_slug = strtolower($feed->service()) ?>
		<a target="_blank" href="<?php echo DOMAIN,"/feeds/authorise/{$feed_slug}/{$feed->attr('id')}" ?>">Authorise</a>
	</div>
	<?php else: ?>
	<div class="tools">
		<a href="<?php echo DOMAIN,'/admin/feeds/fetch/',$feed->attr('id') ?>">Fetch #<?php echo $feed->attr('hashtag') ?></a>
	</div>
	<div class="posts">
		<?php if($posts = $feed->posts()): ?>
		<div class="expander"></div>
		<ul>
		<?php foreach($feed->posts() as $post): ?>
			<li class="post">
				<div class="uid"><?php echo $post->attr('uid') ?></div>
				<div class="tools">
					<a class="visibility_toggle<?php echo ($post->attr('visible')) ? ' visible' : '' ?>" href="#" data-target_collection="feeds" data-target_id="<?php echo $post->attr('id') ?>" data-tooltip="<?php echo Nn::babel('Toggle visibility') ?>">
						<span class="visible"><?php echo Utils::UIIcon("visible"); ?></span>
						<span class="invisible"><?php echo Utils::UIIcon("invisible"); ?></span>
					</a>
				</div>
			</li>
		<?php endforeach; ?>
		</ul>
		<?php else: ?>
		<div class="note"><?php echo Nn::babel('No posts') ?></div>
		<?php endif; ?>
	</div>
	<?php endif ?>
</div>