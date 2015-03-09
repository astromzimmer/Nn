<div id="left">
	<?php Nn::partial('nodetypes'.DS.'_list',array('nodetypes'=>$nodetypes,'nodetype'=>$nodetype)) ?>
</div>
<div id="right">
	<div class="manage">
		<div id="nodetype_form" class="edit_bg">
			<form name="form1" method="post" action="<?php echo DOMAIN.DS.'admin'.DS.'nodetypes'.DS.'update'.DS.$nodetype->attr('id') ?>">
			  <fieldset>
				<legend><?php echo Nn::babel('Name') ?></legend>
			    <input type="text" name="name" class="formfield" id="nameField" value="<?php echo $nodetype->attr('name'); ?>" />
			  </fieldset>
			  <fieldset>
				<legend><?php echo Nn::babel('Attributetypes') ?></legend>
			    <?php foreach($attributetypes as $attributetype): ?>
				  <input type="checkbox" name="attributetypes[]" value="<?php echo $attributetype->attr('id') ?>" class="formfield" id="peopleBox" <?php if($nodetype->has_attributetype($attributetype->attr('id'))) { echo "checked=\"yes\""; } ?> /><?php echo $attributetype->attr('name'); ?><br/>
				<?php endforeach; ?>
			  </fieldset>
			  <div class="submit">
			    <button type="submit" name="submit" id="submit"><?php echo Nn::babel('Save') ?></button>  
			  </div>
			</form>
			<div class="tools">
				<a class="trash" href="<?php echo DOMAIN.DS.'admin'.DS.'nodetypes'.DS.'delete'.DS.$nodetype->attr('id') ?>" data-tooltip="<?php echo Nn::babel('Trash') ?>"><?php Utils::UIIcon('trash'); ?></a>
			</div>
		</div>
	</div>
</div>