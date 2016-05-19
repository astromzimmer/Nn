<div id="left">
	<?php Nn::partial('User','_tree',array('roles'=>$roles)) ?>
</div>
<div id="center">
	<?php Nn::partial('Admin','_logo') ?>
	<?php Nn::partial('Admin','_stats') ?>
</div>