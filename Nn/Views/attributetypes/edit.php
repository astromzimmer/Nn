<div id="left">
	<?php Nn::partial('attributetypes'.DS.'_list',array('attributetypes'=>$attributetypes,'attributetype'=>$attributetype)) ?>
</div>
<div id="right">
	<div class="manage">
		<div id="attributetype_form" class="edit_bg">
			<form name="form1" method="post" action="<?php echo DOMAIN,DS,'admin',DS,'attributetypes',DS,'update',DS,$attributetype->attr('id') ?>">
			  <fieldset>
				<legend><?php echo Nn::babel('Name') ?></legend>
			    <input type="text" name="name" class="formfield" id="nameField" value="<?php echo $attributetype->attr('name'); ?>" />
			  </fieldset>
			  <fieldset>
				<legend><?php echo Nn::babel('Datatype') ?></legend>
			    <select name="datatype" class="formfield" id="datatypeField" />
			    	<?php foreach($datatypes as $datatype): ?>
				    	<option value="<?php echo $datatype; ?>" <?php if($datatype == $attributetype->attr('datatype')) { echo "selected=\"selected\""; } ?>><?php echo $datatype ?></option>
			    	<?php endforeach; ?>
			    </select>
			  </fieldset>
			  <div class="submit">
			    <button type="submit" name="submit" id="submit">submit</button>  
			  </div>
			</form>
			<div class="tools">
				<a class="trash" href="<?php echo DOMAIN,DS,'admin',DS,'attributetypes',DS,'delete',DS,$attributetype->attr('id') ?>"><?php Utils::UIIcon('trash'); ?></a>
			</div>
		</div>
	</div>
</div>