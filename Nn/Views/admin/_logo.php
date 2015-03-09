<div class="logo" style="width:278px;height:28px;">
<?php if(isset($logo) && $image = $logo->data()): ?>
	<?php echo $image->tag(28,true) ?>
<?php else: ?>
	<h2><?php echo (defined('PAGE_NAME')) ? PAGE_NAME : 'Nn' ?></h2>
<?php endif; ?>
</div>