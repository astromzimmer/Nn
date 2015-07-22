<?php $attributes = $node->attributes() ?>
<div class="attributes">
	<div class="maker">
	<?php if($dtype && !$edit_attribute_id): ?>
		<?php Nn::partial('Attribute','_make',array('node'=>$node,'dtype'=>$dtype,'atype'=>$atype)) ?> 
	<?php endif; ?>
	</div>
	<?php if($attributes) : ?>
		<ul id="<?php echo $node->attr('id') ?>_attributes" class="sortable">
		<?php foreach($attributes as $attribute): ?>
		<?php if($data = $attribute->data()): ?>
			<?php $editing = ($edit_attribute_id == $attribute->attr('id')) ?>
			<?php if($editing) : ?>
				<?php Nn::partial('Attribute','_edit',array('node'=>$node,'attribute'=>$attribute)) ?> 
			<?php else : ?>
				<?php Nn::partial('Attribute','_view',array('node'=>$node,'attribute'=>$attribute)) ?> 
			<?php endif; ?>
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