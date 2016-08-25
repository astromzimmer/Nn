<input name="atype_id" type="hidden" value="<?php echo $attributetype->attr('id') ?>" />
<?php if(in_array($attributetype->param('rkey_format'),array('integer','float'))): ?>
<input name="rkey" type="number" value="" class="formfield" placeholder="<?php echo Nn::babel('Enter an number') ?>" id="rkeyField" />
<?php else: ?>
<input name="rkey" type="text" value="" class="formfield" placeholder="<?php echo Nn::babel('Enter a string') ?>" id="rkeyField" />
<?php endif; ?>
<?php if(in_array($attributetype->param('lval_format'),array('integer','float'))): ?>
<input name="lval" type="number" value="" class="formfield" placeholder="<?php echo Nn::babel('Enter an number') ?>" id="valueField" />
<?php else: ?>
<input name="lval" type="text" value="" class="formfield" placeholder="<?php echo Nn::babel('Enter a string') ?>" id="valueField" />
<?php endif; ?>