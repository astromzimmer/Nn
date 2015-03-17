<div id="left">
	<?php Nn::partial('file','_tree',array('files'=>$files)); ?>
</div>
<div id="right">
	<div id="admin_file" class="manage admin_area">
		<div class="header">
			<h3><?php echo htmlspecialchars_decode($file->path()); ?></h3>
			<div class="meta">
				<label>type:</label> <?php echo $file->attr('extension'); ?><br/>
				<label>last edited:</label> <?php echo strftime(DATE_FORMAT,$file->updated_at()); ?><br/>
			</div>
			<div class="tools">
				<a class="edit" href="<?php echo DOMAIN.DS.'admin'.DS.'files'.DS.'edit'.DS.$file->attr('id') ?>"><?php Utils::UIIcon('edit'); ?>
				</a><a class="trash" href="<?php echo DOMAIN.DS.'admin'.DS.'files'.DS.'delete'.DS.$file->attr('id') ?>"><?php Utils::UIIcon('trash'); ?></a>
			</div>
		</div>
		<div id="code-editor" class="read-only"><?php echo $file->escaped_content() ?></div>
	</div>
</div>