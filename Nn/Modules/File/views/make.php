<div id="left">
	<div class="shifted">
		<?php \Utils::partial('Admin'.DS.'views'.DS.'_menu') ?>
	</div>
	<?php \Utils::partial('Files'.DS.'views'.DS.'_tree',array('files'=>$files)); ?>
</div>
<div id="right">
	<div class="manage">
		<div class="columnHeader">
			<a href="<?php echo DOMAIN.DS.'admin' ?>">admin &larr;</a> <a href="<?php echo DOMAIN.DS.'admin'.DS.'files' ?>">files &larr;</a>  <?php echo htmlspecialchars_decode($file->title); ?>
		</div>
		<div id="file_form" class="view">
			<form name="form1" method="post" action="<?php echo DOMAIN.DS.'admin'.DS.'files'.DS.'create' ?>" enctype="multipart/form-data">
			  <input type="hidden" name="parent_id" value="<?php echo $parent_id ?>" />
			  <p>title:<br/>
				<input name="title" type="text" class="formfield" id="titleField" value="" />
			  </p>
			  <p><label>filetype:</label><br/>
			    <select name="filetype_id" class="formfield" id="filetypeField" />
			    	<?php foreach($filetypes as $filetype): ?>
				    	<option value="<?php echo $filetype->id; ?>" <?php if($filetype->id == $file->filetype_id) { echo "selected=\"selected\""; } ?>><?php echo $filetype->name() ?></option>
			    	<?php endforeach; ?>
			    </select>
			  </p>
			  <p>
			  	<button type="submit" name="submit" id="submit" value="submit">create file</button>
			  </p>
			</form>
		</div>
	</div>
</div>