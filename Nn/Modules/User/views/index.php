<div id="left">
	<?php Nn::partial('user','_tree',array('roles'=>$roles)) ?>
</div>
<div id="right">
	<?php Nn::partial('admin','_logo') ?>
	<?php Nn::partial('admin','_stats') ?>
</div>