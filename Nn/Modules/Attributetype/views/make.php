<div id="left">
	<?php Nn::partial('Attributetype','_list',array('attributetypes'=>$attributetypes)) ?>
</div>
<div id="center">
	<div class="manage">
		<div id="attributetype_form" class="edit_bg">
			<form name="form1" method="post" action="<?php echo Nn::s('DOMAIN'),'/admin/attributetypes/create' ?>">
				<fieldset>
					<legend><?php echo Nn::babel('Name') ?></legend>
					<input type="text" name="name" class="formfield" id="nameField" value="" />
				</fieldset>
				<fieldset>
					<legend><?php echo Nn::babel('Datatype') ?></legend>
					<select name="datatype" class="formfield" id="datatypeField">
						<?php foreach($datatypes as $datatype): ?>
					    	<option value="<?php echo $datatype; ?>" data-url_param="<?php echo $datatype ?>"><?php echo $datatype ?></option>
						<?php endforeach; ?>
					</select>
					<span id="paramsContainer"></span>
				</fieldset>
				<fieldset>
					<legend><?php echo Nn::babel('Icon') ?>:</legend>
					<input type="radio" name="icon" value="null" checked="checked"><?php echo Nn::babel('None') ?>&nbsp;
					<span class="fontawesome">
						<?php foreach($icons as $key=>$val): ?>
						<input type="radio" name="icon" value="<?php echo $key ?>" ><?php echo $val ?>&nbsp;
						<?php endforeach; ?>
					</span>
				</fieldset>
				<div id="defaultContainer"></div>
				<div class="submit">
			    <button type="submit" name="submit" id="submit" class="half"><?php echo Nn::babel('Save') ?></button>  
			  </div>
			</form>
		</div>
	</div>
</div>