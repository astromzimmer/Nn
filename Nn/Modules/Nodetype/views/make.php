<div id="left">
	<?php Nn::partial('nodetype','_list',array('nodetypes'=>$nodetypes)) ?>
</div>
<div id="right">
	<div class="manage">
		<div id="nodetype_form" class="edit_bg">
			<form name="form1" method="post" action="<?php echo DOMAIN.DS.'admin'.DS.'nodetypes'.DS.'create' ?>">
			  <fieldset>
				<legend><?php echo Nn::babel('Name') ?></legend>
			    <input type="text" name="name" class="formfield" id="nameField" value="" />
			  </fieldset>
			  <?php if($attributetypes): ?>
			  <fieldset>
				<legend><?php echo Nn::babel('Supported Attributetypes') ?></legend>
			    <?php foreach($attributetypes as $attributetype): ?>
				    <input type="checkbox" name="attributetypes[]" value="<?php echo $attributetype->attr('id') ?>" class="formfield" id="peopleBox" /><?php echo $attributetype->attr('name'); ?><br/>
				<?php endforeach; ?>
			  </fieldset>
			  <?php endif; ?>
			  <?php if($nodetypes): ?>
			  <fieldset>
				<legend><?php echo Nn::babel('Supported Nodetypes') ?></legend>
			    <?php foreach($nodetypes as $nodetype): ?>
				    <input type="checkbox" name="nodetypes[]" value="<?php echo $nodetype->attr('id') ?>" class="formfield" id="peopleBox" /><?php echo $nodetype->attr('name'); ?><br/>
				<?php endforeach; ?>
			  </fieldset>
			  <?php endif; ?>
			  <br/>
			  <div class="submit">
			    <button type="submit" name="submit" id="submit" class="half"><?php echo Nn::babel('save') ?></button>  
			  </div>
			</form>
		</div>
	</div>
</div>