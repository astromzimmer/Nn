<div id="left">
	<?php Nn::partial('Nodetype','_list',array('nodetypes'=>$nodetypes,'nodetype'=>$nodetype)) ?>
</div>
<div id="center">
	<div class="manage">
		<div id="nodetype_form" class="edit_bg">
			<form name="form1" method="post" action="<?php echo Nn::settings('DOMAIN').'/admin/nodetypes/update/'.$nodetype->attr('id') ?>">
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
				  <input type="checkbox" name="attributetypes[]" value="<?php echo $attributetype->attr('id') ?>" class="formfield" id="attributetypesBox" <?php if($nodetype->has_attributetype($attributetype->attr('id'))) { echo "checked=\"yes\""; } ?> /><?php echo $attributetype->attr('name'); ?><br/>
				<?php endforeach; ?>
			</fieldset>
			<fieldset>
				<legend><?php echo Nn::babel('Supported Nodetypes') ?></legend>
				<?php foreach($nodetypes as $nt): ?>
				  <input type="checkbox" name="nodetypes[]" value="<?php echo $nt->attr('id') ?>" class="formfield" id="peopleBox" <?php if($nodetype->has_nodetype($nt->attr('id'))) { echo "checked=\"yes\""; } ?> /><?php echo $nt->attr('name'); ?><br/>
				<?php endforeach; ?>
			</fieldset>
			<?php if($layouts): ?>
				<fieldset>
					<legend><?php echo Nn::babel('Print Layout') ?>:</legend>
					<select name="layout_id">
						<option value="null"><?php echo Nn::babel('None') ?></option>
						<?php foreach($layouts as $layout): ?>
						<option value="<?php echo $layout->attr('id') ?>" <?php if($nodetype->attr('layout_id') == $layout->attr('id')) { echo "selected=\"selected\""; } ?>><?php echo $layout->attr('name') ?></option>
						<?php endforeach; ?>
					</select>
				</fieldset>
			<?php endif ?>
			<fieldset>
				<legend><?php echo Nn::babel('Colour') ?></legend>
				<input type="checkbox" name="has_colour" class="formfield" id="colourBox" <?php if($nodetype->attr('colour')) { echo "checked=\"yes\""; } ?> />
				<input type="color" name="colour" value="<?php echo $nodetype->attr('colour') ?>" class="formfield" id="colourField" /><br/>
			</fieldset>
			<fieldset>
				<legend><?php echo Nn::babel('Icon') ?>:</legend>
				<input type="text" name="icon" class="formfield" id="iconField" value="<?php echo $nodetype->attr('icon'); ?>" />
			</fieldset>
			<div class="submit">
			    <a href="<?php echo Nn::settings('DOMAIN'),DS,'admin',DS,'nodetypes',DS,'delete',DS,$nodetype->attr('id') ?>" class="delete button half float"><?php echo Nn::babel('Delete') ?></a>
			    <button type="submit" name="submit" id="submit" class="half float"><?php echo Nn::babel('Save') ?></button>
			</div>
			</form>
		</div>
	</div>
</div>