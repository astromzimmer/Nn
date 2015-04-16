<input name="atype_id" type="hidden" value="<?php echo $attributetype->attr('id') ?>" />
<?php if($attributetype->params()['size'] == 'long'): ?>
	<textarea name="content" rows="12" class="md formfield<?php echo (array_key_exists('rte',$attributetype->params())) ? " rte" : "" ?>" id="contentField"></textarea>
<?php else: ?>
	<input name="content" type="text" class="md formfield<?php echo (array_key_exists('rte',$attributetype->params())) ? " rte" : "" ?>" id="contentField" />
<?php endif ?>