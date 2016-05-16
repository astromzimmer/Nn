<?php if(isset($publication)): ?>
	<div class="publication">
		<section class="cover" data-id="0">
			<div class="page A4 back">
				<div class="imprint">Diese Brosch&uuml;re wurde mit Hilfe eines digitalen Publikationssystems generiert.</div>
			</div>
			<div class="page A4">
				<?php Nn::partial('Publication','_cover',['publication'=>$publication]); ?>
			</div>
		</section>
		<?php foreach($publication->nodes() as $node): ?>
		<section class="<?php echo strtolower($node->section()->layout()->attr('name')) ?>" data-id="<?php echo $node->section()->attr('id') ?>">
			<?php echo $node->section()->markup() ?>
		</section>
		<?php endforeach; ?>
	</div>
<?php endif ?>
<div class="footer"><div class="button neutral print" data-tooltip="<?php echo Nn::babel('Print') ?>"><?php echo Utils::UIIcon("print"); ?></div></div>