<div class="borderline">
	<div id="attributetypes" class="menu">
		<ul id="0_attributetypes" class="sortable">
		<?php foreach($attributetypes as $at): ?>
			<li class="attributetype<?php if(isset($attributetype) && $at == $attributetype) echo ' focus' ?>" id="attributetype_<?php echo $at->attr('id') ?>">
				<div class="handle"></div>
				<div class="grouper">
					<div class="label">
						<a href="<?php echo DOMAIN,DS,'admin',DS,'attributetypes',DS,'edit',DS,$at->attr('id') ?>"><?php echo $at->attr('name'); ?></a>
					</div>
					<div class="tools">
						<div class="tool handle"></div>
						<a class="tool trash" href="<?php echo DOMAIN.DS.'admin'.DS.'attributetypes'.DS.'delete'.DS.$at->attr('id') ?>" data-tooltip="<?php echo Nn::babel('trash') ?>"><?php echo Utils::UIIcon('trash'); ?></a>
					</div>
				</div>
			</li>
		<?php endforeach ?>
			<li>
				<div class="add"><a href="<?php echo DOMAIN,DS,'admin',DS,'attributetypes',DS,'make' ?>">+</a></div>
			</li>
		</ul>
	</div>
</div>