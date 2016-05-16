<div id="node">
	<div class="node" data-id="<?php echo $node->attr('id') ?>">
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
				<!-- <li class="meta">
					<label><?php echo Nn::babel('Slug') ?>:</label> <?php echo Utils::ellipsis($node->slug(),32); ?>
				</li> -->
				<li class="tools">
					<a class="edit" href="<?php echo DOMAIN.DS.'admin'.DS.'nodes'.DS.'edit'.DS.$node->attr('id') ?>" data-tooltip="<?php echo Nn::babel('edit') ?>" data-target="center" data-ajax><?php echo Utils::UIIcon('edit'); ?>
					</a><a class="trash" href="<?php echo DOMAIN.DS.'admin'.DS.'nodes'.DS.'delete'.DS.$node->attr('id') ?>" data-tooltip="<?php echo Nn::babel('trash') ?>" data-target="center" data-ajax><?php echo Utils::UIIcon('trash'); ?>
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
				<?php foreach($attributetypes as $attributetype) : ?><a
					href="<?php echo '/admin/nodes/'.Nn::settings('NODE_VIEW').'/'.$node->attr('id').DS.$attributetype->attr('id') ?>"
					data-tooltip="<?php echo Nn::babel('Add') ?> <?php echo $attributetype->attr('name') ?>"
					data-target="center"
					data-ajax
				><?php echo $attributetype->icon(); ?></a><?php endforeach; ?>
				<?php endif; ?>
			</div>
			<div class="tools right">
			<?php if($attributes = $node->attributes()) : ?>
				<a class="collapse" href="#" data-tooltip="<?php echo Nn::babel('Collapse all') ?>"><?php echo Utils::UIIcon("collapse"); ?></a>
			<?php endif; ?>
			</div>
		</div>
		<div id="admin_node" class="manage admin_area">
			<?php Nn::partial('Attribute','_list',array('node'=>$node,'dtype'=>$dtype,'atype'=>$atype,'attr_id'=>$attr_id)); ?>
		</div>
	</div>
</div>