<?php if($table->hasFile()): ?>
	<div class="table" data-id="<?php echo $table->attr('id') ?>">
		<div class="description"><?php echo $table->attr('description') ?></div>
		<?php if($content = $table->content()): ?>
			<?php $needs_header = true; ?>
			<table>
			<?php foreach($content as $row_name => $row): ?>
				<tr class="<?php if($needs_header) echo 'th' ?>">
				<?php foreach ($row as $cell_name => $cell): ?>
					<td class="cell"><?php echo $cell == 'HEADER' ? $cell_name : $cell ?></td>
				<?php endforeach ?>
				</tr>
				<?php $needs_header = false ?>
			<?php endforeach ?>
			</ul>
		<?php endif ?>
	</div>
<?php else: ?>
	No document file found.
<?php endif; ?>