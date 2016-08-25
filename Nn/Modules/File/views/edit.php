<div id="left">
	<div class="shifted">
		<?php Nn::partial('files'.DS.'_menu') ?>
	</div>
	<?php Nn::partial('files'.DS.'_tree',array('files'=>$files)); ?>
</div>
<div id="right">
	<div id="file_form" class="view">
		<form name="form1" method="post" action="<?php echo Nn::s('DOMAIN').'/admin/files/update/'.$file->attr('id') ?>" enctype="multipart/form-data">
			<div class="header">
				<h3><input name="path" type="text" class="formfield" id="pathField" value="<?php echo $file->path() ?>" /></h3>
			</div>
			<div id="code-editor"><?php echo $file->escaped_content() ?></div>
			<div class="submit">
				<a href="<?php echo Nn::s('DOMAIN').'/admin/files/view/'.$file->attr('id') ?>" class="cancel button">cancel</a>
				<button type="submit" name="submit" id="submit" class="save">save</button>
			</div>
		</form>
	</div>
</div>