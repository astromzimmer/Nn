
<?php if(is_int($key)): ?>
<li class="file" id="file_<?php echo $file->attr('id') ?>" data-id="<?php echo $file->attr('id') ?>">
	<div class="grouper">
		<div class="label">
			<a href="<?php echo Nn::s('DOMAIN').'/admin/files/view/'.$file->attr('id') ?>"><?php echo $file->basename() ?></a>
		</div>
	</div>
</li>
<?php else: ?>
<li class="dir" id="dir_<?php echo $key ?>" data-id="<?php echo $key ?>">
	<div class="grouper">
		<div class="expander">
			
		</div>
		<div class="label">
			<?php echo $key ?>
		</div>
	</div>
	<ul id="<?php echo $key ?>_files" class="submenu sortable">
		<?php foreach($file as $k => $f) : ?>
			<?php Nn::partial('file','_list',array('key'=>$k,'file'=>$f)); ?>
		<?php endforeach; ?>
	</ul>
</li>
<?php endif; ?>