<div class="borderline">
	<div id="styles" class="menu">
		<ul id="0_styles" class="">
		<?php foreach($styles as $s): ?>
			<li class="style<?php if(isset($style) && $s == $style) echo ' focus' ?>" id="style_<?php echo $s->attr('id') ?>">
				<div class="grouper">
					<div class="label">
						<a href="<?php echo Nn::settings('DOMAIN'),'/admin/styles/edit/',$s->attr('id') ?>"><?php echo $s->attr('name'); ?></a>
					</div>
				</div>
			</li>
		<?php endforeach ?>
			<li>
				<div class="add"><a href="<?php echo Nn::settings('DOMAIN'),'/admin/styles/make' ?>">+</a></div>
			</li>
		</ul>
	</div>
</div>