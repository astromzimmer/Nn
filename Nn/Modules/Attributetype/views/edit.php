<div id="left">
	<?php Nn::partial('Attributetype','_list',array('attributetypes'=>$attributetypes,'attributetype'=>$attributetype)) ?>
</div>
<div id="center">
	<div class="manage">
		<div id="attributetype_form" class="edit_bg">
			<form name="form1" method="post" action="<?php echo Nn::s('DOMAIN'),'/admin/attributetypes/update/',$attributetype->attr('id') ?>">
			  <fieldset>
				<legend><?php echo Nn::babel('Name') ?></legend>
			    <input type="text" name="name" class="formfield" id="nameField" value="<?php echo $attributetype->attr('name'); ?>" />
			  </fieldset>
			  <fieldset>
				<legend><?php echo Nn::babel('Datatype') ?></legend>
			    <select name="datatype" class="formfield" id="datatypeField" />
			    	<?php foreach($datatypes as $datatype): ?>
				    	<option value="<?php echo $datatype; ?>" data-url_param="<?php echo $datatype ?>" <?php if($datatype == $attributetype->attr('datatype')) { echo "selected=\"selected\""; } ?>><?php echo $datatype ?></option>
			    	<?php endforeach; ?>
			    </select>
			    <span id="paramsContainer">
			    	<?php Nn::partial('Attributetype','_params',['params'=>$datatype_params]); ?>
			    </span>
			  </fieldset>
			  <div id="defaultContainer">
			  <?php if($datatype_default): ?>
			  	<fieldset>
					<legend><?php echo Nn::babel('Default') ?></legend>
					<?php if($datatype_default == 'textarea'): ?>
						<textarea type="text" name="default_value"><?php echo $attributetype->attr('default_value'); ?></textarea>
					<?php endif ?>
				</fieldset>
			  <?php endif ?>
			  </div>
			  <fieldset>
				<legend><?php echo Nn::babel('Icon') ?>:</legend>
				<input type="text" name="icon" class="formfield" id="iconField" value="<?php echo $attributetype->attr('icon'); ?>" />
			</fieldset>
			  <div class="submit">
				  <a href="<?php echo Nn::s('DOMAIN'),'/admin/attributetypes/delete/',$attributetype->attr('id') ?>" class="delete button half float"><?php echo Nn::babel('Delete') ?></a>
			    <button type="submit" name="submit" id="submit" class="half float"><?php echo Nn::babel('Save') ?></button>
			  </div>
			</form>
		</div>
	</div>
</div>