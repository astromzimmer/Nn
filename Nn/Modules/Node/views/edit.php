<div id="left">
	<?php Nn::partial('node','_tree',array('nodes'=>$nodes,'node'=>$node)); ?>
</div>
<div id="right">
	<div class="manage">
		<div id="node_form" class="view">
			<form name="form1" method="post" action="<?php echo DOMAIN.DS.'admin'.DS.'nodes'.DS.'update'.DS.$node->attr('id') ?>" enctype="multipart/form-data">
				<fieldset>
					<legend><?php echo Nn::babel('Title') ?>:</legend>
					<input name="title" type="text" class="formfield" id="titleField" value="<?php echo $node->attr('title') ?>" autofocus />
				</fieldset>
				<fieldset>
					<legend><?php echo Nn::babel('Slug') ?>:</legend>
					<input name="slug" type="text" class="formfield" id="slugField" value="<?php echo $node->slug() ?>" />
				</fieldset>
				<fieldset>
					<legend><?php echo Nn::babel('Type') ?>:</legend>
					<select name="nodetype_id" class="formfield" id="nodetypeField" />
						<?php foreach($nodetypes as $nodetype): ?>
						<option value="<?php echo $nodetype->attr('id'); ?>" <?php if($nodetype->attr('id') == $node->attr('nodetype_id')) { echo "selected=\"selected\""; } ?>><?php echo $nodetype->attr('name') ?></option>
						<?php endforeach; ?>
					</select>
				</fieldset>
				<fieldset>
					<legend><?php echo Nn::babel('Parent') ?>:</legend>
					<select name="parent_id" class="formfield" id="parentField" />
						<option value="0" <?php if($node->attr('parent_id') == 0) { echo "selected=\"selected\""; } ?>>ROOT</option>
						<?php foreach($parents as $parent): ?>
						<option value="<?php echo $parent->attr('id'); ?>" <?php if($parent->attr('id') == $node->attr('parent_id')) { echo "selected=\"selected\""; } ?>><?php echo Utils::ellipse($parent->title()) ?></option>
						<?php endforeach; ?>
					</select>
				</fieldset>
				<div class="submit">
					<a href="<?php echo DOMAIN.DS.'admin'.DS.'nodes'.DS.'view'.DS.$node->attr('id') ?>" class="cancel button half float"><?php echo Nn::babel('Cancel') ?></a>
					<button type="submit" name="submit" id="submit" class="float half save"><?php echo Nn::babel('Save') ?></button>
				</div>
			</form>
		</div>
	</div>
</div>