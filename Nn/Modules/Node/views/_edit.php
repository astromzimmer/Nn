<div id="node">
	<div class="manage">
		<div id="node_form" class="view">
			<form name="form1" method="post" action="<?php echo DOMAIN.'/admin/nodes/update/'.$node->attr('id') ?>" data-target="center" enctype="multipart/form-data">
				<fieldset>
					<legend><?php echo Nn::babel('Title') ?>:</legend>
					<input name="title" type="text" class="formfield" id="titleField" value="<?php echo $node->attr('title') ?>" autofocus />
				</fieldset>
				<fieldset>
					<legend><?php echo Nn::babel('Permalink') ?>:</legend>
					<input name="permalink" type="text" class="formfield" id="permalinkField" value="<?php echo $node->permalink() ?>" />
				</fieldset>
				<fieldset>
					<legend><?php echo Nn::babel('Type') ?>:</legend>
					<select name="nodetype_id" class="formfield" id="nodetypeField" >
						<?php foreach($nodetypes as $nodetype): ?>
						<option value="<?php echo $nodetype->attr('id'); ?>" <?php if($nodetype->attr('id') == $node->attr('nodetype_id')) { echo "selected=\"selected\""; } ?>><?php echo $nodetype->attr('name') ?></option>
						<?php endforeach; ?>
					</select>
				</fieldset>
				<fieldset>
					<legend><?php echo Nn::babel('Parent') ?>:</legend>
					<select name="parent_id" class="formfield" id="parentField" >
						<option value="0" <?php if($node->attr('parent_id') == 0) { echo "selected=\"selected\""; } ?>>ROOT</option>
						<?php foreach($parents as $parent): ?>
						<option value="<?php echo $parent->attr('id'); ?>" <?php if($parent->attr('id') == $node->attr('parent_id')) { echo "selected=\"selected\""; } ?>><?php echo Utils::ellipsis($parent->title(),32) ?></option>
						<?php endforeach; ?>
					</select>
				</fieldset>
				<div class="submit">
					<a href="<?php echo DOMAIN.'/admin/nodes/'.Nn::settings('NODE_VIEW').'/'.$node->attr('id') ?>" class="cancel button half float" data-target="center" data-ajax><?php echo Nn::babel('Cancel') ?></a>
					<button type="submit" name="submit" id="submit" class="float half save"><?php echo Nn::babel('Save') ?></button>
				</div>
			</form>
		</div>
	</div>
</div>