<div id="left">
	<?php Nn::partial('Layout','_list',['layouts'=>$layouts,'layout'=>$layout]) ?>
</div>
<div id="center">
	<div class="manage">
		<div id="layout_form" class="edit_bg">
			<form name="form1" method="post" action="<?php echo Nn::s('DOMAIN').'/admin/layouts/update/'.$layout->attr('id') ?>">
				<fieldset>
					<legend><?php echo Nn::babel('Name') ?></legend>
				    <input type="text" name="name" class="formfield" id="nameField" value="<?php echo $layout->attr('name'); ?>">
				</fieldset>
				<fieldset>
					<legend><?php echo Nn::babel('Template') ?></legend>
				    <textarea name="template" id="" cols="30" rows="16"><?php echo $layout->attr('template'); ?></textarea>
				</fieldset>
				<fieldset>
					<legend><?php echo Nn::babel('Rules') ?></legend>
				    <textarea name="rules" id="" cols="30" rows="10"><?php echo $layout->attr('rules'); ?></textarea>
				</fieldset>
				<div class="submit">
					<button type="submit" name="submit" id="submit" class="half"><?php echo Nn::babel('save') ?></button>  
				</div>
			</form>
		</div>
	</div>
</div>