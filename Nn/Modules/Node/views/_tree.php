<?php
	// $nodes = Nn\Modules\Node\Node::find(array('parent_id'=>0),null,'position');
	$node = isset($node) ? $node : false;
?>
<div class="borderline">
	<div id="nodes" class="admin_area menu tree">
		<input type="text" id="filter" placeholder="<?php echo Nn::babel('Filter') ?>">
		<ul id="0_nodes" class="sortable">
			<?php if($nodes): ?>
			<?php foreach($nodes as $n): ?>
				<?php Nn::partial('Node','_list',array('n'=>$n,'node'=>$node)); ?>
			<?php endforeach ?>
			<?php endif; ?>
			<li>
				<div class="add"><a
					href="<?php echo Nn::settings('DOMAIN').'/admin/nodes/make' ?>"
					data-tooltip="<?php echo Nn::babel('New node') ?>"
					data-target="center"
					data-ajax
					>+</a></div>
			</li>
		</ul>
	</div>
</div>