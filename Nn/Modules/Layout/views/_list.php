<div class="borderline">
	<div id="layouts" class="menu">
		<ul id="0_layouts" class="sortable">
		<?php if($layouts): ?>
		<?php foreach($layouts as $lot): ?>
			<li class="layout<?php if(isset($layout) && $lot == $layout) echo ' focus' ?>" id="nodetype_<?php echo $lot->attr('id') ?>">
				<div class="handle"></div>
				<div class="grouper">
					<div class="label">
						<a href="<?php echo Nn::s('DOMAIN').'/admin/layouts/edit/'.$lot->attr('id') ?>"><?php echo $lot->attr('name'); ?></a>
					</div>
				</div>
			</li>
		<?php endforeach ?>
		<?php endif ?>
			<li>
				<div class="add">
					<a href="<?php echo Nn::s('DOMAIN').'/admin/layouts/make' ?>">+</a>
				</div>
			</li>
		</ul>
	</div>
</div>