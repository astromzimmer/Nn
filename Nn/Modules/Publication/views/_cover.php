<?php if($coverTree = $publication->coverTree()): ?>
	<ul>
	<?php foreach($coverTree as $title): ?>
		<li><?php echo $title ?></li>
	<?php endforeach ?>
	</ul>
<?php endif; ?>
<div class="logo"></div>