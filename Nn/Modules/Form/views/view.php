<div id="left">
	<?php Nn::partial('Form','_list',array('forms'=>$forms,'form'=>$form)) ?>
</div>
<div id="center">
	<div id="forms" class="manage">
	<?php if($entries): ?>
		<div class="header">
			<a class="button neutral" href="/admin/forms/download/<?php echo $form->attr('id') ?>"><?php echo Nn::babel('Download CSV') ?></a>
			<!-- <a class="button neutral" href="/admin/forms/download/<?php echo $form->attr('id') ?>/all"><?php echo Nn::babel('Download CSV + files') ?></a> -->
		</div>
		<ul class="entries">
		<?php foreach($entries as $entry): ?>
			<li class="entry">
				<ul class="data">
				<?php foreach($entry->data() as $key => $value): ?>
					<li class="field">
						<strong><?php echo $key ?>:</strong> <?php echo $value ?>
					</li>
				<?php endforeach ?>
				</ul>
				<ul class="files">
				<?php if($files = $entry->files()): ?>
					<?php foreach($entry->files() as $name => $file): ?>
					<li class="files">
						<strong><?php echo $name ?>:</strong> <a href="<?php echo $entry->attachmentPath($file) ?>"><?php echo $file['name'] ?></a>
					</li>
					<?php endforeach ?>
				<?php endif ?>
				</ul>
				<div class="tools">
					<a
						class="trash"
						href="<?php echo '/admin/forms/delete/',$entry->attr('id') ?>"
						data-tooltip="<?php echo Nn::babel('trash') ?>"
						><?php echo Utils::UIIcon("trash"); ?>
					</a>
				</div>
			</li>
		<?php endforeach ?>
		</ul>
	<?php endif ?>
	</div>
</div>