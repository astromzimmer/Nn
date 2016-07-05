<div id="node">
	<div class="manage">
		<div id="node_form" class="view">
			<form name="form1" method="post" action="<?php echo DOMAIN.'/admin/nodes/create' ?>" data-target="center" enctype="multipart/form-data">
				<input type="hidden" name="parent_id" value="<?php echo $node->attr('parent_id') ?>">
				<fieldset>
					<legend><?php echo Nn::babel('Title') ?>:</legend>
					<input name="title" type="text" class="formfield" id="titleField" value="" autofocus />
				</fieldset>
				<fieldset>
					<legend><?php echo Nn::babel('Type') ?>:</legend>
					<?php if($nodetypes): ?>
						<select name="nodetype_id" class="formfield" id="nodetypeField" >
						<?php foreach($nodetypes as $nodetype): ?>
							<option value="<?php echo $nodetype->attr('id'); ?>" ><?php echo $nodetype->attr('name') ?></option>
						<?php endforeach; ?>
						</select>
					<?php else: ?>
						<div class="error"><?php echo Nn::babel('Please enable at least one Nodetype as ROOT') ?></div>
					<?php endif; ?>
				</fieldset>
				<div class="submit">
					<a href="<?php echo DOMAIN.'/admin/nodes/'.Nn::settings('NODE_VIEW').'/'.$node->attr('parent_id') ?>" class="cancel button half float" data-target="center" data-ajax><?php echo Nn::babel('Cancel') ?></a>
					<button type="submit" <?php if(!$nodetypes) echo 'disabled="disabled"' ?> name="submit" id="submit" class="half float save"><?php echo Nn::babel('Save') ?></button>
				</div>
			</form>
		</div>
	</div>
</div>