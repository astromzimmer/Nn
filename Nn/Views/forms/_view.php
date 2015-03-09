<form method="POST" action="<?php echo DOMAIN.DS.'forms'.DS.'submit'.DS.$form->attr('id') ?>" class="<?php if($form->attr('id')) echo $form->attribute()->attributetype()->cleanName() ?>">
	<input name="spmchk" type="text" id="spmchk" value="hmn" style="display: none;" />
	<div id="form<?php if($form->attr('id')) echo htmlentities($form->attr('id')); ?>-content" data-id="<?php if($form->attr('id')) echo $form->attr('id') ?>"><?php if($form->attr('id')) echo $form->content(); ?></div>
</form>