<?php
	if(!isset($publication)) {
		$publication = \Nn\Modules\Publication\Publication::find(1);
	}
?>
<?php if($publication): ?>
<div class="sections menu">
	<ul>
		<li class="section cover exclude" id="section_0" data-id="0"><div class="grouper">
			<div class="label"><a href="<?php echo DOMAIN.'/admin/publications/view/'.$publication->attr('id').'/0' ?>" data-ajax><?php echo Nn::babel('Cover') ?></a></div>
		</div></li>
	</ul>
	<ul id="1_sections" class="chapters sortable">
		<?php foreach($publication->nodes() as $node): ?>
			<li class="section" id="section_<?php echo $node->attr('id') ?>" data-id="<?php echo $node->section()->attr('id') ?>">
				<div class="grouper">
					<div class="label"><a href="<?php echo DOMAIN.'/admin/publications/view/'.$publication->attr('id').'/'.$node->section()->attr('id') ?>" data-ajax><?php echo $node->attr('title') ?></a></div>
					<div class="tools">
						<div class="tool handle"></div>
						<div class="tool remove" data-id="<?php echo $node->attr('id') ?>" data-tooltip="<?php echo Nn::babel('remove') ?>">&times;</div>
						<div class="tool bubble" data-id="<?php echo $node->attr('id') ?>" data-tooltip="<?php echo Nn::babel('toggle index') ?>">
							<input type="checkbox" <?php echo ($node->bubbling) ? 'checked="true"' : '' ?>>
						</div>
					</div>
				</div>
			</li>
		<?php endforeach; ?>
	</ul>
</div>
<?php endif ?>