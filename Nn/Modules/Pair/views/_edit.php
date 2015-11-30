<input name="atype_id" type="hidden" value="<?php echo $attributetype->attr('id') ?>" />
<?php if(in_array($attributetype->param('key_format'),array('integer','float'))): ?>
<input name="key" type="number" value="<?php echo $keyval->attr('key') ?>" class="formfield" placeholder="<?php echo Nn::babel('Enter an number') ?>" id="keyField" />
<?php else: ?>
<input name="key" type="text" value="<?php echo $keyval->attr('key') ?>" class="formfield" placeholder="<?php echo Nn::babel('Enter a string') ?>" id="keyField" />
<?php endif; ?>
<?php if(in_array($attributetype->param('value_format'),array('integer','float'))): ?>
<input name="value" type="number" value="<?php echo $keyval->attr('value') ?>" class="formfield" placeholder="<?php echo Nn::babel('Enter an number') ?>" id="valueField" />
<?php else: ?>
<input name="value" type="text" value="<?php echo $keyval->attr('value') ?>" class="formfield" placeholder="<?php echo Nn::babel('Enter a string') ?>" id="valueField" />
<?php endif; ?>