<div id="left">
	<?php Nn::partial('user','_tree',array('roles'=>$roles,'role'=>$role,'focus'=>$role)) ?>
</div>
<div id="right">
	<div class="manage">
		<div id="role_form" class="view">
		<?php if(isset($role)) : ?>
		<div id="role_form" class="view">
			<form name="form1" method="post" action="<?php echo DOMAIN.'/admin/users/update_role/'.$role->attr('id'); ?>" enctype="multipart/form-data">
			  <fieldset>
				<legend><?php echo Nn::babel('Name') ?></legend>
			    <input name="name" type="text" class="formfield" id="NField" value="<?php echo $role->attr('name') ?>" />
			  </fieldset>
		<?php else : ?>
			<form name="form1" method="post" action="<?php echo DOMAIN.'/admin/users/create_role'; ?>" enctype="multipart/form-data">
			  <fieldset>
				<legend><?php echo Nn::babel('Name') ?></legend>
			    <input name="name" type="text" class="formfield" id="NField" value="" />
			  </fieldset>
		<?php endif; ?>
			  <div class="submit">
			    <button type="submit" name="submit" id="submit"><?php echo Nn::babel('Save') ?></button>  
			  </div>
			</form>
			<div class="tools">
				<a class="trash" href="<?php echo DOMAIN.'/admin/users/delete_role/'.$role->attr('id') ?>" data-tooltip="<?php echo Nn::babel('Trash') ?>"><?php Utils::UIIcon('trash'); ?></a>
			</div>
		</div>
	</div>
</div>