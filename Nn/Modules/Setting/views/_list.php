<div class="borderline">
	<div id="settings" class="menu">
		<ul id="0_settings" class="">
		<?php foreach($settings as $s): ?>
			<li class="setting<?php if(isset($setting) && $s == $setting) echo ' focus' ?>" id="setting_<?php echo $s->attr('id') ?>">
				<div class="grouper">
					<div class="label">
						<a href="<?php echo Nn::settings('DOMAIN'),'/admin/settings/edit/',$s->attr('id') ?>"><?php echo $s->attr('name'); ?></a>
					</div>
				</div>
			</li>
		<?php endforeach ?>
			<li>
				<div class="add"><a href="<?php echo Nn::settings('DOMAIN'),'/admin/settings/make' ?>">+</a></div>
			</li>
		</ul>
	</div>
</div>