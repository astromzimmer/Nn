<div class="borderline">
	<div id="nodes" class="admin_area menu tree">
		<ul id="0_nodes" class="sortable">
			<?php if($nodes): ?>
			<?php foreach($nodes as $n): ?>
				<?php Nn::partial('nodes'.DS.'_list',array('n'=>$n,'node'=>$node)); ?>
			<?php endforeach ?>
			<?php endif; ?>
			<li>
				<div class="add"><a href="<?php echo DOMAIN.'/admin/nodes/make' ?>" data-tooltip="<?php echo Nn::babel('New node') ?>"><?php echo Utils::UIIcon('plus'); ?></a></div>
			</li>
		</ul>
	</div>
</div>