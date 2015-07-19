<div id="left">
	<?php Nn::partial('Nodetype','_list',array('nodetypes'=>$nodetypes,'nodetype'=>$nodetype)) ?>
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
				<legend><?php echo Nn::babel('Can be used as ROOT') ?></legend>
			    <input type="checkbox" name="can_be_root" value="1" class="formfield" id="canBeRootBox" <?php if($nodetype->canBeRoot()) { echo "checked=\"yes\""; } ?> /><br/>
			  </fieldset>
			  <fieldset>
				<legend><?php echo Nn::babel('Supported Attributetypes') ?></legend>
			    <?php foreach($attributetypes as $attributetype): ?>
				  <input type="checkbox" name="attributetypes[]" value="<?php echo $attributetype->attr('id') ?>" class="formfield" id="peopleBox" <?php if($nodetype->has_attributetype($attributetype->attr('id'))) { echo "checked=\"yes\""; } ?> /><?php echo $attributetype->attr('name'); ?><br/>
				<?php endforeach; ?>
			  </fieldset>
			  <fieldset>
				<legend><?php echo Nn::babel('Supported Nodetypes') ?></legend>
			    <?php foreach($nodetypes as $nt): ?>
				  <input type="checkbox" name="nodetypes[]" value="<?php echo $nt->attr('id') ?>" class="formfield" id="peopleBox" <?php if($nodetype->has_nodetype($nt->attr('id'))) { echo "checked=\"yes\""; } ?> /><?php echo $nt->attr('name'); ?><br/>
				<?php endforeach; ?>
			  </fieldset>
			  <fieldset>
					<legend><?php echo Nn::babel('Icon') ?>:</legend>
					<select name="icon" class="iconselect fontawesome" />
						<option value="null"><?php echo Nn::babel('None') ?></option>
						<?php foreach($icons as $key=>$val): ?>
						<option value="<?php echo $key ?>" <?php if($nodetype->attr('icon') == $key) { echo "selected=\"selected\""; } ?>><?php echo $val ?></option>
						<?php endforeach; ?>
					</select>
				</fieldset>
			  <div class="submit">
			    <button type="submit" name="submit" id="submit" class="half"><?php echo Nn::babel('Save') ?></button>  
			  </div>
			</form>
			<div class="tools">
				<a class="trash" href="<?php echo DOMAIN.DS.'admin'.DS.'nodetypes'.DS.'delete'.DS.$nodetype->attr('id') ?>" data-tooltip="<?php echo Nn::babel('Trash') ?>"><?php Utils::UIIcon('trash'); ?></a>
			</div>
		</div>
	</div>
</div>