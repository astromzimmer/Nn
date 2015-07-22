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
					href="<?php echo DOMAIN.'/admin/nodes/make' ?>"
					data-tooltip="<?php echo Nn::babel('New node') ?>"
					data-target="right"
					data-ajax
					><?php echo Utils::UIIcon('plus'); ?></a></div>
			</li>
		</ul>
	</div>
</div>