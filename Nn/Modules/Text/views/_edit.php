<?php if($attributetype->param('size') == 'long'): ?>
	<textarea name="content" rows="12" class="md formfield<?php echo (array_key_exists('rte',$attributetype->params())) ? " rte" : "" ?>" id="contentField" autofocus><?php echo $text->content(true) ?></textarea>
<?php else: ?>
	<input name="content" type="text" value="<?php echo $text->attr('content') ?>" class="formfield" id="contentField" autofocus />
<?php endif ?>