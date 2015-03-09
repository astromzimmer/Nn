<div id="left">
	<?php Nn::partial('users'.DS.'_tree',array('roles'=>$roles)) ?>
</div>
<div id="right">
	<?php Nn::partial('admin'.DS.'_logo') ?>
	<?php Nn::partial('admin'.DS.'_stats') ?>
</div>