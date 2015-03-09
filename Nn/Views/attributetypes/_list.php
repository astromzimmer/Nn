<div class="borderline">
	<div id="attributetypes" class="menu">
		<ul id="0_attributetypes" class="sortable">
		<?php foreach($attributetypes as $at): ?>
			<li class="attributetype<?php if(isset($attributetype) && $at == $attributetype) echo ' focus' ?>" id="attributetype_<?php echo $at->attr('id') ?>">
				<div class="handle"></div>
				<div class="label">
					<a href="<?php echo DOMAIN,DS,'admin',DS,'attributetypes',DS,'edit',DS,$at->attr('id') ?>"><?php echo $at->attr('name'); ?></a>
				</div>
			</li>
		<?php endforeach ?>
			<li>
				<div class="add"><a href="<?php echo DOMAIN,DS,'admin',DS,'attributetypes',DS,'make' ?>"><?php echo Utils::UIIcon('plus'); ?></a></div>
			</li>
		</ul>
	</div>
</div>