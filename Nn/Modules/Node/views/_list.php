<?php $is_active = ($node && (in_array($n->attr('id'),$node->navigation_tree()))) ?>
<?php $is_in_focus = ($node && ($n == $node)) ?>
<li
	style="background-color:<?php echo $n->nodetype()->attr('colour') ?>;"
	class="node<?php if($is_active) echo ' active expanded'; if($is_in_focus) echo ' focus' ?>"
	id="node_<?php echo $n->attr('id') ?>"
	data-id="<?php echo $n->attr('id') ?>"
	data-label="<?php echo $n->attr('title') ?>">
	<div class="grouper">
		<?php if($n->nodetype()->nodetypes()): ?>
		<div class="expander<?php if($is_active) echo ' expanded' ?>"></div>
		<?php endif; ?>
		<div class="label">
			<a
				href="<?php echo Nn::settings('DOMAIN').'/admin/nodes/'.Nn::settings('NODE_VIEW').'/'.$n->attr('id') ?>"
				data-target="center"
				data-ajax
				><?php if($icon = $n->nodetype()->attr('icon')): ?><span
					class="fa <?php echo $icon ?>"
					></span><?php endif ?><?php echo Utils::ellipsis($n->attr('title'),30); ?></a>
		</div>
		<div class="tools">
			<div class="tool handle" data-tooltip="<?php echo Nn::babel('Sort') ?>"></div>
			<a class="tool link" href="<?php echo $n->permalink() ?>" target="_blank" data-tooltip="<?php echo Nn::babel('Public link') ?>"></a>
			<?php if(Nn::s('PRINT')): ?>
			<div class="tool pub" data-tooltip="<?php echo Nn::babel('Add to publication') ?>"></div>
			<?php endif; ?>
		</div>
	</div>
	<?php if($n->nodetype()->nodetypes()): ?>
	<ul id="<?php echo $n->attr('id') ?>_nodes" class="submenu sortable<?php if($is_active) echo ' expanded' ?>">
		<?php if($children = $n->children()): ?>
		<?php foreach($n->children() as $subnode) : ?>
			<?php Nn::partial('Node','_list',array('n'=>$subnode,'node'=>$node)); ?>
		<?php endforeach; ?>
		<?php endif; ?>
		<li>
			<div class="add"><a
				href="<?php echo Nn::settings('DOMAIN').'/admin/nodes/make/in/'.$n->attr('id') ?>"
				data-tooltip="<?php echo Nn::babel('New node in'); ?> <?php echo $n->attr('title'); ?>"
				data-target="center"
				data-ajax
				>+</a></div>
		</li>
	</ul>
	<?php endif; ?>
</li>