<?php $is_active = ($node && (in_array($n,$node->navigation_tree()))) ?>
<?php $is_in_focus = ($node && ($n == $node)) ?>
<li class="node<?php if($is_active) echo ' active expanded'; if($is_in_focus) echo ' focus' ?>" id="node_<?php echo $n->attr('id') ?>" data-id="<?php echo $n->attr('id') ?>">
	<?php if($n->nodetype()->nodetypes()): ?>
	<div class="expander<?php if($is_active) echo ' expanded' ?>"></div>
	<?php endif; ?>
	<div class="handle" data-tooltip="<?php echo Nn::babel('Sort') ?>"></div>
	<a class="link" href="<?php echo $n->permalink() ?>" target="_blank" data-tooltip="<?php echo Nn::babel('Public link') ?>"></a>
	<div class="label<?php if($is_active) echo ' active'; if($is_in_focus) echo ' focus' ?>">
		<a href="<?php echo DOMAIN.'/admin/nodes/view/'.$n->attr('id') ?>"><?php echo $n->attr('title') ?></a>
	</div>
	<?php if($n->nodetype()->nodetypes()): ?>
	<ul id="<?php echo $n->attr('id') ?>_nodes" class="submenu sortable<?php if($is_active) echo ' expanded' ?>">
		<?php if($children = $n->children()): ?>
		<?php foreach($n->children() as $subnode) : ?>
			<?php Nn::partial('Node','_list',array('n'=>$subnode,'node'=>$node)); ?>
		<?php endforeach; ?>
		<?php endif; ?>
		<li>
			<div class="add"><a href="<?php echo DOMAIN.'/admin/nodes/make/in/'.$n->attr('id') ?>" data-tooltip="<?php echo Nn::babel('New node in'); ?> <?php echo $n->attr('title'); ?>"><?php echo Utils::UIIcon('plus'); ?></a></span>
		</li>
	</ul>
	<?php endif; ?>
</li>