<div id="left">
	<?php Nn::partial('Nodetype','_list',array('nodetypes'=>$nodetypes)) ?>
</div>
<div id="right">
	<div class="manage">
		<div id="nodetype_form" class="edit_bg">
			<form name="form1" method="post" action="<?php echo Nn::settings('DOMAIN').DS.'admin'.DS.'nodetypes'.DS.'create' ?>">
				<fieldset>
					<legend><?php echo Nn::babel('Name') ?></legend>
				    <input type="text" name="name" class="formfield" id="nameField" value="" />
				</fieldset>
				<fieldset>
					<legend><?php echo Nn::babel('Can be used as ROOT') ?></legend>
				    <input type="checkbox" name="can_be_root" value="1" class="formfield" id="canBeRootBox" /><br/>
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
					<legend><?php echo Nn::babel('Supported child Nodetypes') ?></legend>
				    <?php foreach($nodetypes as $nodetype): ?>
					    <input type="checkbox" name="nodetypes[]" value="<?php echo $nodetype->attr('id') ?>" class="formfield" id="peopleBox" /><?php echo $nodetype->attr('name'); ?><br/>
					<?php endforeach; ?>
				</fieldset>
				<?php endif; ?>
				<fieldset>
					<legend><?php echo Nn::babel('Icon') ?>:</legend>
					<input type="radio" name="icon" value="null" checked="checked"><?php echo Nn::babel('None') ?>&nbsp;
					<span class="fontawesome">
						<?php foreach($icons as $key=>$val): ?>
						<input type="radio" name="icon" value="<?php echo $key ?>" ><?php echo $val ?>&nbsp;
						<?php endforeach; ?>
					</span>
				</fieldset>
				<br/>
				<div class="submit">
					<button type="submit" name="submit" id="submit" class="half"><?php echo Nn::babel('save') ?></button>  
				</div>
			</form>
		</div>
	</div>
</div>