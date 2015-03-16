<input type="hidden" name="MAX_FILE_SIZE" value="10000000" />
<input name="atype_id" type="hidden" value="<?php echo $attributetype->attr('id') ?>" />
<input name="title" type="text" class="formfield" id="altField" value="" placeholder="<?php echo Nn::babel('Title') ?>"/>
<br>
<textarea name="description" class="formfield" id="descriptionArea" rows="6" placeholder="<?php echo Nn::babel('Description') ?>"></textarea>
<input type="file" name="file_upload" />