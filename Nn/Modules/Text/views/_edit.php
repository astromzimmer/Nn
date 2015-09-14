<?php if($attributetype->params()['size'] == 'long'): ?>
	<textarea name="content" rows="12" class="md formfield<?php echo (array_key_exists('rte',$attributetype->params())) ? " rte" : "" ?>" id="contentField" autofocus><?php echo $text->attr('content') ?></textarea>
<?php else: ?>
	<input name="content" type="text" value="<?php echo $text->attr('content') ?>" class="formfield" id="contentField" autofocus />
<?php endif ?>