<input name="atype_id" type="hidden" value="<?php echo $attributetype->attr('id') ?>" />
<?php if($attributetype->params()['size'] == 'long'): ?>
	<textarea name="content" rows="12" class="md formfield<?php echo (array_key_exists('rte',$attributetype->params())) ? " rte" : "" ?>" id="contentField" autofocus></textarea>
<?php else: ?>
	<input name="content" type="text" class="formfield<?php echo (array_key_exists('rte',$attributetype->params())) ? " rte" : "" ?>" id="contentField" autofocus />
<?php endif ?>