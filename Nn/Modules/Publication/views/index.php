<div id="left">
	<?php Nn::partial('Node','_tree',['nodes'=>$nodes]); ?>
</div>
<div id="center">
	<?php Nn::partial('Admin','_logo') ?>
</div>
<div id="right">
<?php if(Nn::settings('print')): ?>
	<?php Nn::partial('Publication','_view'); ?>
<?php endif ?>
</div>