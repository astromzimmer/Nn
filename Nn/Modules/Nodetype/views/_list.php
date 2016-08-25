<div class="borderline">
	<div id="nodetypes" class="menu">
		<ul id="0_nodetypes" class="sortable">
		<?php foreach($nodetypes as $nt): ?>
			<li
				style="background-color:<?php echo $nt->attr('colour') ?>;"
				class="nodetype<?php if(isset($nodetype) && $nt == $nodetype) echo ' focus' ?>" id="nodetype_<?php echo $nt->attr('id') ?>">
				<div class="grouper">
					<div class="label">
						<a href="<?php echo Nn::settings('DOMAIN').'/admin/nodetypes/edit/'.$nt->attr('id') ?>">
							<span class="fa <?php echo $nt->attr('icon') ?>"></span>
							<?php echo $nt->attr('name'); ?>
						</a>
					</div>
					<div class="tools">
						<div class="tool handle"></div>
						<a class="tool trash" href="<?php echo Nn::settings('DOMAIN').'/admin/nodetypes/delete/'.$nt->attr('id') ?>" data-tooltip="<?php echo Nn::babel('trash') ?>"><?php echo Utils::UIIcon('trash'); ?></a>
					</div>
				</div>
			</li>
		<?php endforeach ?>
			<li>
				<div class="add">
					<a href="<?php echo Nn::settings('DOMAIN').'/admin/nodetypes/make' ?>">+</a>
				</div>
			</li>
		</ul>
	</div>
</div>