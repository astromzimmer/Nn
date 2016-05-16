<li id="attribute_<?php echo $attribute->attr('id'); ?>" class="aMD attribute editing">
	<a class="anchor" name="attribute_<?php echo $attribute->attr('id'); ?>"></a>
	<form
		name="form1"
		method="post"
		action="<?php echo '/admin/'.$attribute->data()->collectionName().'/update/'.$attribute->data()->attr('id') ?>"
		data-target="node"
		enctype="multipart/form-data">
		<?php if($attributetypes = $node->nodetype()->attributetypes()) : ?>
		<label class="handle">
			<select name="attributetype_id" class="formfield" id="attributetypeField" >
			<?php
				$current_attributetype = $attribute->attributetype();
			?>
	    	<?php foreach($attributetypes as $attributetype) : ?>
    		<?php if($attributetype->attr('datatype') == $current_attributetype->attr('datatype')): ?>
				<option value="<?php echo $attributetype->attr('id'); ?>" <?php if($attributetype->attr('id') == $current_attributetype->attr('id')) { echo "selected=\"selected\""; } ?>><?php echo $attributetype->attr('name') ?></option>
			<?php endif; ?>
			<?php endforeach; ?>
			</select>
		</label>
		<?php endif; ?>
		<input name="node_id" type="hidden" value="<?php echo $node->attr('id') ?>" />
		<?php Nn::partial($attribute->datatype(),'_edit',array('attributetype'=>$attribute->attributetype(),'node'=>$node,'attribute'=>$attribute,strtolower($attribute->datatype())=>$attribute->data())); ?>
		<div class="submit">
			<a
				href="<?php echo '/admin/nodes/'.Nn::settings('NODE_VIEW').'/'.$node->attr('id') ?>"
				class="cancel button half float"
				data-target="node"
				data-ajax
				><?php echo Nn::babel('Cancel') ?></a>
			<button type="submit" name="submit" id="submit" class="save half float"><?php echo Nn::babel('Save') ?></button>
		</div>
	</form>
</li>