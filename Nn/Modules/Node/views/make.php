<div id="left">
	<?php Nn::partial('Node','_tree',array('nodes'=>$nodes,'node'=>$node)); ?>
</div>
<div id="right">
	<div class="manage">
		<div id="node_form" class="view">
			<form name="form1" method="post" action="<?php echo DOMAIN.DS.'admin'.DS.'nodes'.DS.'create' ?>" enctype="multipart/form-data">
				<input type="hidden" name="parent_id" value="<?php echo $node->attr('parent_id') ?>">
				<fieldset>
					<legend><?php echo Nn::babel('Title') ?>:</legend>
					<input name="title" type="text" class="formfield" id="titleField" value="" autofocus />
				</fieldset>
				<fieldset>
					<legend><?php echo Nn::babel('Type') ?>:</legend>
					<select name="nodetype_id" class="formfield" id="nodetypeField" />
						<?php foreach($nodetypes as $nodetype): ?>
						<option value="<?php echo $nodetype->attr('id'); ?>" ><?php echo $nodetype->attr('name') ?></option>
						<?php endforeach; ?>
					</select>
				</fieldset>
				<div class="submit">
					<a href="<?php echo DOMAIN.DS.'admin'.DS.'nodes'.DS.'view'.DS.$node->attr('parent_id') ?>" class="cancel button half float"><?php echo Nn::babel('Cancel') ?></a>
					<button type="submit" name="submit" id="submit" class="half float save"><?php echo Nn::babel('Save') ?></button>
				</div>
			</form>
		</div>
	</div>
</div>