<?php $attributes = $node->attributes() ?>
<div class="attributes">
	<?php if($dtype && !$edit_attribute_id): ?>
	<div class="new attribute">
		<label><?php echo strtolower($atype->attr('name')); ?></label>
		<form name="form1" method="post" action="<?php echo '/admin/'.strtolower(Utils::plurify($dtype)).'/create' ?>" enctype="multipart/form-data">
			<input name="node_id" type="hidden" value="<?php echo $node->attr('id') ?>" />
			<?php Nn::partial($dtype),'_new',array('node'=>$node,'attributetype'=>$atype)) ?> 
			<div class="submit">
				<a href="<?php echo '/admin/nodes/view/'.$node->attr('id') ?>" class="cancel button"><?php echo Nn::babel('Cancel') ?></a>
				<button type="submit" name="submit" id="submit" class="save"><?php echo Nn::babel('Save') ?></button>
			</div>
		</form>
	</div>
	<?php endif; ?>
	<?php if($attributes) : ?>
	<ul id="<?php echo $node->attr('id') ?>_attributes" class="sortable">
	<?php foreach($attributes as $attribute): ?>
	<?php if($data = $attribute->data()): ?>
	<?php $editing = ($edit_attribute_id == $attribute->attr('id')) ?>
		<li id="attribute_<?php echo $attribute->attr('id'); ?>" class="aMD attribute<?php if($editing) echo ' editing' ?>">
			<a class="anchor" name="attribute_<?php echo $attribute->attr('id'); ?>"></a>
			<?php if($editing) : ?>
			<form name="form1" method="post" action="<?php echo '/admin/'.$attribute->data()->collectionName().'/update/'.$attribute->data()->attr('id') ?>" enctype="multipart/form-data">
				<?php if($attributetypes = $node->nodetype()->attributetypes()) : ?>
				<fieldset>
					<legend><?php echo Nn::babel('Attributetype') ?></legend>
					<select name="attributetype_id" class="formfield" id="attributetypeField" />
					<?php
						$current_attributetype = $attribute->attributetype();
					?>
			    	<?php foreach($attributetypes as $attributetype) : ?>
		    		<?php if($attributetype->attr('datatype') == $current_attributetype->attr('datatype')): ?>
				    	<option value="<?php echo $attributetype->attr('id'); ?>" <?php if($attributetype->attr('id') == $current_attributetype->attr('id')) { echo "selected=\"selected\""; } ?>><?php echo $attributetype->attr('name') ?></option>
				    <?php endif; ?>
			    	<?php endforeach; ?>
				    </select>
				</fieldset>
				<?php endif; ?>
				<input name="node_id" type="hidden" value="<?php echo $node->attr('id') ?>" />
				<?php Nn::partial($attribute->data()->collectionName(),'_edit',array('attributetype'=>$attribute->attributetype(),'node'=>$node,'attribute'=>$attribute,strtolower($attribute->datatype())=>$attribute->data())); ?>
				<div class="submit">
					<a href="<?php echo '/admin/nodes/view/'.$node->attr('id') ?>" class="cancel button half"><?php echo Nn::babel('Cancel') ?></a>
					<button type="submit" name="submit" id="submit" class="save half"><?php echo Nn::babel('Save') ?></button>
				</div>
			</form>
			<?php else : ?>
			<label class="handle"><?php echo strtolower($attribute->attributetype()->attr('name')); ?></label>
			<?php Nn::partial($attribute->public_view(),array(strtolower($attribute->datatype())=>$attribute->data())); ?>
			<div class="tools">
				<a class="edit" href="<?php echo '/admin/attributes/edit/',$attribute->attr('id'),'#attribute_',$attribute->attr('id') ?>" data-tooltip="<?php echo Nn::babel('edit') ?>"><?php echo Utils::UIIcon("edit"); ?>
				</a><a class="trash" href="<?php echo '/admin/attributes/delete/',$attribute->attr('id') ?>" data-tooltip="<?php echo Nn::babel('trash') ?>"><?php echo Utils::UIIcon("trash"); ?>
				</a><a class="visibility_toggle<?php echo ($attribute->attr('visible')) ? ' visible' : '' ?>" href="#" data-target_collection="attributes" data-target_id="<?php echo $attribute->attr('id') ?>" data-tooltip="<?php echo Nn::babel('Toggle visibility') ?>">
					<span class="visible"><?php echo Utils::UIIcon("visible"); ?></span>
					<span class="invisible"><?php echo Utils::UIIcon("invisible"); ?></span>
				</a><a class="collapse" href="#" data-tooltip="<?php echo Nn::babel('Collapse') ?>"><?php echo Utils::UIIcon("collapse"); ?></a>
			</div>
			<?php endif; ?>
		</li>
	<?php else: ?>
		<li class="attribute" id="attribute_<?php echo $attribute->attr('id'); ?>">
			<div class="edit_bg">
				No Data
			</div>
			<div class="tools">
				<a class="trash" href="<?php echo '/admin/attributes/delete',DS,$attribute->attr('id') ?>" data-tooltip="<?php echo Nn::babel('trash') ?>"><?php echo Utils::UIIcon("trash"); ?></a>
			</div>
		</li>
	<?php endif; ?>
	<?php endforeach ?>
	</ul>
	<?php endif; ?>
</div>