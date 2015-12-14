<li id="attribute_<?php echo $attribute->attr('id'); ?>" class="aMD attribute" data-id="<?php echo $attribute->attr('id') ?>">
	<a class="anchor" name="attribute_<?php echo $attribute->attr('id'); ?>"></a>
	<label class="handle"><?php echo strtolower($attribute->attributetype()->attr('name')); ?></label>
	<?php Nn::partial($attribute->admin_view(),array(strtolower($attribute->datatype())=>$attribute->data())); ?>
	<div class="tools">
		<a
			class="edit"
			href="<?php echo '/admin/nodes/view/',$node->attr('id'),'/',$attribute->attributetype()->attr('id').'/',$attribute->attr('id') ?>"
			data-tooltip="<?php echo Nn::babel('edit') ?>"
			data-target="right"
			data-ajax
			><?php echo Utils::UIIcon("edit"); ?>
		</a><a
			class="trash"
			href="<?php echo '/admin/attributes/delete/',$attribute->attr('id') ?>"
			data-tooltip="<?php echo Nn::babel('trash') ?>"
			data-ajax
			><?php echo Utils::UIIcon("trash"); ?>
		</a><a class="visibility_toggle<?php echo ($attribute->attr('visible')) ? ' visible' : '' ?>" href="#" data-target_collection="attributes" data-target_id="<?php echo $attribute->attr('id') ?>" data-tooltip="<?php echo Nn::babel('Toggle visibility') ?>">
			<span class="visible"><?php echo Utils::UIIcon("visible"); ?></span>
			<span class="invisible"><?php echo Utils::UIIcon("invisible"); ?></span>
		</a><a class="collapse" href="#" data-tooltip="<?php echo Nn::babel('Collapse') ?>"><?php echo Utils::UIIcon("collapse"); ?></a>
	</div>
</li>