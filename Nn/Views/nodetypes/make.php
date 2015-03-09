<div id="left">
	<?php Nn::partial('nodetypes'.DS.'_list',array('nodetypes'=>$nodetypes)) ?>
</div>
<div id="right">
	<div class="manage">
		<div id="nodetype_form" class="edit_bg">
			<form name="form1" method="post" action="<?php echo DOMAIN.DS.'admin'.DS.'nodetypes'.DS.'create' ?>">
			  <fieldset>
				<legend><?php echo Nn::babel('Name') ?></legend>
			    <input type="text" name="name" class="formfield" id="nameField" value="" />
			  </fieldset>
			  <fieldset>
				<legend><?php echo Nn::babel('Attributetypes') ?></legend>
			    <?php foreach($attributetypes as $attributetype): ?>
				    <input type="checkbox" name="attributetypes[]" value="<?php echo $attributetype->attr('id') ?>" class="formfield" id="peopleBox" /><?php echo $attributetype->attr('name'); ?><br/>
				<?php endforeach; ?>
			  </fieldset>
			  <br/>
			  <div class="submit">
			    <button type="submit" name="submit" id="submit"><?php echo Nn::babel('save') ?></button>  
			  </div>
			</form>
		</div>
	</div>
</div>