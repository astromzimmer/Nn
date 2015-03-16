<div id="page">
	<?php foreach($pages as $page): ?>
	<?php if ($attributes = $page->attributes('Title, Ingress')): ?>
		<?php foreach($attributes as $attribute): ?>
			<?php echo Nn::partial($attribute->public_view(),array(strtolower($attribute->datatype())=>$attribute->data())); ?>
		<?php endforeach; ?>
	<?php endif ?>
	<?php endforeach; ?>
	<?php foreach($posts as $post): ?>
	<?php if ($attributes = $post->attributes_except('Date, Paragraph, Lightbox Image')): ?>
		<?php $sub_header = $post->parent()->title(); ?>
		<div class="padder"></div>
		<div class="sub_header dontend"><?php echo $sub_header ?></div>
		<div class="header dontend" data-id="<?php echo $post->attr('id') ?>">
			<div class="title"><?php echo $post->title() ?></div>
			<div class="date"><?php echo $post->date() ?></div>
		</div>
		<?php if ($images = $post->attributes(['Thumbnail'])): ?>
			<div class="images">
			<?php foreach($images as $image): ?>
				<?php echo Nn::partial($image->public_view(),array(strtolower($image->datatype())=>$image->data())); ?>
			<?php endforeach; ?>
			</div>
		<?php endif ?>
		<?php if ($texts = $post->attributes('Ingress, Paragraph')): ?>
			<?php foreach($texts as $text): ?>
				<?php echo Nn::partial($text->public_view(),array(strtolower($text->datatype())=>$text->data())); ?>
			<?php endforeach; ?>
		<?php endif ?>
	<?php endif ?>
	<?php endforeach; ?>
</div>
<div id="footer">
	<div class="impressum">
		<div class="fb-like" data-href="http://www.facebook.com/astromzimmer" data-width="12" data-layout="button" data-action="like" data-show-faces="false" data-share="false"></div>
		<div id="fb-root"></div>
		<script>(function(d, s, id) {
		  var js, fjs = d.getElementsByTagName(s)[0];
		  if (d.getElementById(id)) return;
		  js = d.createElement(s); js.id = id;
		  js.src = "http://connect.facebook.net/en_GB/all.js#xfbml=1&appId=188474151324215";
		  fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));</script>
	</div>
</div>