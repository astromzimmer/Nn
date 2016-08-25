<div id="left">
	<?php Nn::partial('Publication','_template_list',array('templates'=>$templates,'template'=>$template)) ?>
</div>
<div id="right">
	<div class="manage">
		<div id="template_form" class="edit_bg">
			<form name="form1" method="post" action="<?php echo Nn::settings('DOMAIN').DS.'admin'.DS.'templates'.DS.'update'.DS.$template->attr('id') ?>">
			<fieldset>
				<legend><?php echo Nn::babel('Name') ?></legend>
				<input type="text" name="name" class="formfield" id="nameField" value="<?php echo $template->attr('name'); ?>" />
			</fieldset>
			<fieldset>
				<legend><?php echo Nn::babel('Can be used as ROOT') ?></legend>
				<textarea name="content" id="" cols="30" rows="10"></textarea>
			</fieldset>
			<fieldset>
				<legend><?php echo Nn::babel('Supported Attributetypes') ?></legend>
				<?php foreach($attributetypes as $attributetype): ?>
				  <input type="checkbox" name="attributetypes[]" value="<?php echo $attributetype->attr('id') ?>" class="formfield" id="peopleBox" <?php if($template->has_attributetype($attributetype->attr('id'))) { echo "checked=\"yes\""; } ?> /><?php echo $attributetype->attr('name'); ?><br/>
				<?php endforeach; ?>
			</fieldset>
			<fieldset>
				<legend><?php echo Nn::babel('Supported Nodetypes') ?></legend>
				<?php foreach($templates as $nt): ?>
				  <input type="checkbox" name="templates[]" value="<?php echo $nt->attr('id') ?>" class="formfield" id="peopleBox" <?php if($template->has_template($nt->attr('id'))) { echo "checked=\"yes\""; } ?> /><?php echo $nt->attr('name'); ?><br/>
				<?php endforeach; ?>
			</fieldset>
			<?php if($templates): ?>
				<fieldset>
					<legend><?php echo Nn::babel('Print Template') ?>:</legend>
					<select name="template_id">
						<option value="null"><?php echo Nn::babel('None') ?></option>
						<?php foreach($templates as $template): ?>
						<option value="<?php echo $template->attr('id') ?>" <?php if($template->attr('template_id') == $template->attr('id')) { echo "selected=\"selected\""; } ?>><?php echo $template->attr('title') ?></option>
						<?php endforeach; ?>
					</select>
				</fieldset>
			<?php endif ?>
			<fieldset>
				<legend><?php echo Nn::babel('Icon') ?>:</legend>
				<input type="radio" name="icon" value="null" <?php if(empty($template->attr('icon'))) { echo "checked=\"checked\""; } ?>><?php echo Nn::babel('None') ?>&nbsp;
				<span class="fontawesome">
					<?php foreach($icons as $key=>$val): ?>
					<input type="radio" name="icon" value="<?php echo $key ?>" <?php if($template->attr('icon') == $key) { echo "checked=\"checked\""; } ?>><?php echo $val ?>&nbsp;
					<?php endforeach; ?>
				</span>
			</fieldset>
			<div class="submit">
			    <button type="submit" name="submit" id="submit" class="half"><?php echo Nn::babel('Save') ?></button>  
			</div>
			</form>
			<div class="tools">
				<a class="trash" href="<?php echo Nn::settings('DOMAIN').DS.'admin'.DS.'templates'.DS.'delete'.DS.$template->attr('id') ?>" data-tooltip="<?php echo Nn::babel('Trash') ?>"><?php Utils::UIIcon('trash'); ?></a>
			</div>
		</div>
	</div>
</div>