<?php if($forms): ?>
<div class="borderline">
	<div id="forms" class="menu">
		<ul id="0_forms" class="sortable">
		<?php foreach($forms as $frm): ?>
			<li class="form<?php if(isset($form) && $frm == $form) echo ' focus' ?>" id="form_<?php echo $frm->attr('id') ?>">
				<div class="grouper">
					<div class="label">
						<a href="<?php echo Nn::s('DOMAIN').'/admin/forms/view/'.$frm->attr('id') ?>">
							<span class="fa <?php echo $frm->attr('icon') ?>"></span>
							<?php echo $frm->attr('name'); ?>
						</a>
					</div>
				</div>
			</li>
		<?php endforeach ?>
		</ul>
	</div>
</div>
<?php endif ?>