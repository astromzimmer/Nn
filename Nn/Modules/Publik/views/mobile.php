<div id="page">
	<?php foreach($pages as $page): ?>
	<?php if ($attributes = $page->attributes_except('lightbox image')): ?>
		<?php foreach($attributes as $attribute): ?>
			<?php echo partial($attribute->public_view(),array(strtolower($attribute->datatype())=>$attribute->data())); ?>
		<?php endforeach; ?>
	<?php endif ?>
	<?php endforeach; ?>
	<div class="splitme"></div>
	<?php foreach($posts as $post): ?>
	<?php if ($attributes = $post->attributes_except('date, paragraph, lightbox image')): ?>
		<?php $sub_header = $post->parent()->title; ?>
		<div class="padder"></div>
		<div class="sub_header dontend"><?php echo $sub_header ?></div>
		<div class="header dontend" data-id="<?php echo $post->id ?>">
			<div class="title"><?php echo $post->title() ?></div>
			<div class="date"><?php echo $post->date() ?></div>
		</div>
		<?php if ($images = $post->attributes('image, lightbox image')): ?>
			<div class="images">
			<?php foreach($images as $image): ?>
				<?php echo partial($image->public_view(),array(strtolower($image->datatype())=>$image->data())); ?>
			<?php endforeach; ?>
			</div>
		<?php endif ?>
		<?php if ($texts = $post->attributes('ingress, paragraph')): ?>
			<?php foreach($texts as $text): ?>
				<?php echo partial($text->public_view(),array(strtolower($text->datatype())=>$text->data())); ?>
			<?php endforeach; ?>
		<?php endif ?>
	<?php endif ?>
	<?php endforeach; ?>
</div>