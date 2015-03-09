<?php if(is_int($key)): ?>
<li class="file" id="file_<?php echo $file->attr('id') ?>" data-id="<?php echo $file->attr('id') ?>">
	<div class="label">
		<a href="<?php echo DOMAIN.DS.'admin'.DS.'files'.DS.'view'.DS.$file->attr('id') ?>"><?php echo $file->basename() ?></a>
	</div>
</li>
<?php else: ?>
<li class="dir" id="dir_<?php echo $key ?>" data-id="<?php echo $key ?>">
	<div class="expander">
		
	</div>
	<div class="label">
		<?php echo $key ?>
	</div>
	<ul id="<?php echo $key ?>_files" class="submenu sortable">
		<?php foreach($file as $k => $f) : ?>
			<?php Nn::partial('files'.DS.'_list',array('key'=>$k,'file'=>$f)); ?>
		<?php endforeach; ?>
	</ul>
</li>
<?php endif; ?>