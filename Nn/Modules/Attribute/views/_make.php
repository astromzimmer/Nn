<div class="new attribute">
	<label><?php echo strtolower($atype->attr('name')); ?></label>
	<form
		name="form1"
		method="post"
		action="<?php echo '/admin/'.strtolower(Utils::plurify($dtype)).'/create' ?>"
		data-target="right"
		enctype="multipart/form-data">
		<input name="node_id" type="hidden" value="<?php echo $node->attr('id') ?>" />
		<?php Nn::partial($dtype,'_new',array('node'=>$node,'attributetype'=>$atype)) ?> 
		<div class="submit">
			<a
				href="<?php echo '/admin/nodes/view/'.$node->attr('id') ?>"
				class="cancel button half float"
				data-target="right"
				data-pattern="admin\/nodes\/view\/(\d+)$"
				><?php echo Nn::babel('Cancel') ?></a>
			<button type="submit" name="submit" id="submit" class="save half float"><?php echo Nn::babel('Save') ?></button>
		</div>
	</form>
</div>