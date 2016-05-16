<div id="left">
	<?php Nn::partial('Layout','_list',array('layouts'=>$layouts)) ?>
</div>
<div id="center">
	<div class="manage">
		<div id="layout_form" class="edit_bg">
			<form name="form1" method="post" action="<?php echo DOMAIN.'/admin/layouts/create' ?>">
				<fieldset>
					<legend><?php echo Nn::babel('Name') ?></legend>
				    <input type="text" name="name" class="formfield" id="nameField" value="" />
				</fieldset>
				<fieldset>
					<legend><?php echo Nn::babel('Markdown') ?></legend>
				    <textarea class="md clean" name="content" id="" cols="30" rows="16"></textarea>
				</fieldset>
				<fieldset>
					<legend><?php echo Nn::babel('Rules') ?></legend>
				    <textarea name="rules" id="" cols="30" rows="10"></textarea>
				</fieldset>
				<div class="submit">
					<button type="submit" name="submit" id="submit" class="half"><?php echo Nn::babel('save') ?></button>  
				</div>
			</form>
		</div>
	</div>
</div>