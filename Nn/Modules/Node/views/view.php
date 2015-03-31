<div id="left">
	<?php Nn::partial('node','_tree',array('nodes'=>$nodes,'node'=>$node)); ?>
</div>
<div id="right">
	<div class="header maximised">
		<ul>
			<li class="meta">
				<label><?php echo Nn::babel('Type') ?>:</label> <?php echo $node->type(); ?>	
			</li>
			<li class="meta">
				<label><?php echo Nn::babel('Created') ?>:</label> <?php echo strftime(DATE_FORMAT,$node->attr('created_at')); ?>	
			</li>
			<li class="meta">
				<label><?php echo Nn::babel('Last edited') ?>:</label> <?php echo strftime(DATE_FORMAT,$node->attr('updated_at')); ?>	
			</li>
			<li class="meta">
				<label><?php echo Nn::babel('Slug') ?>:</label> <?php echo Utils::ellipse($node->slug(),124); ?>
			</li>
			<li class="tools">
				<a class="edit" href="<?php echo DOMAIN.DS.'admin'.DS.'nodes'.DS.'edit'.DS.$node->attr('id') ?>" data-tooltip="<?php echo Nn::babel('edit') ?>"><?php echo Utils::UIIcon('edit'); ?>
				</a><a class="trash" href="<?php echo DOMAIN.DS.'admin'.DS.'nodes'.DS.'delete'.DS.$node->attr('id') ?>" data-tooltip="<?php echo Nn::babel('trash') ?>"><?php echo Utils::UIIcon('trash'); ?>
				</a><a class="visibility_toggle<?php echo ($node->attr('visible')) ? ' visible' : '' ?>" href="#" data-target_collection="nodes" data-target_id="<?php echo $node->attr('id') ?>" data-tooltip="<?php echo Nn::babel('Toggle visibility') ?>">
					<span class="visible"><?php echo Utils::UIIcon("visible"); ?></span>
					<span class="invisible"><?php echo Utils::UIIcon("invisible"); ?></span>
				</a>
			</li>
		</ul>
		<div class="title">
			<?php echo htmlspecialchars_decode($node->attr('title')); ?>
		</div>
		<div class="new tools">
			<?php if($attributetypes = $node->nodetype()->attributetypes()) : ?>
			<?php foreach($attributetypes as $attributetype) : ?><a href="<?php echo '/admin/nodes/view/'.$node->attr('id').DS.$attributetype->attr('name') ?>" data-tooltip="<?php echo Nn::babel('Add') ?> <?php echo $attributetype->attr('name') ?>"><?php echo $attributetype->icon(); ?></a><?php endforeach; ?>
			<?php endif; ?>
		</div>
		<div class="tools right">
		<?php if($attributes = $node->attributes()) : ?>
			<a class="collapse" href="#" data-tooltip="<?php echo Nn::babel('Collapse all') ?>"><?php echo Utils::UIIcon("collapse"); ?></a>
		<?php endif; ?>
		</div>
	</div>
	<div id="admin_node" class="manage admin_area">
		<?php Nn::partial('attribute','_admin_list',array('node'=>$node,'dtype'=>$dtype,'atype'=>$atype,'edit_attribute_id'=>$edit_attribute_id)); ?>
	</div>
</div>