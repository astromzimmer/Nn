<div class="handle">
	<div class="toggle button layout"><?php echo Utils::UIIcon("paper"); ?></div>
	<a href="<?php echo Nn::settings('DOMAIN').'/admin/nodes/reset/'.$section->attr('node_id') ?>" class="reset button"><?php echo Utils::UIIcon("paper_refresh"); ?></a>
</div>
<div class="section">
	<section class="<?php echo strtolower($section->layout()->attr('name')) ?>" data-id="<?php echo $section->attr('id') ?>" data-rules="<?php echo htmlentities($section->layout()->attr('rules'),ENT_QUOTES) ?>" data-template="<?php echo htmlentities($section->layout()->attr('template'),ENT_QUOTES) ?>">
		<?php echo $section->markup() ?>
	</section>
</div>