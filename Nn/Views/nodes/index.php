<div id="left">
	<?php Nn::partial('nodes'.DS.'_tree',array('nodes'=>$nodes,'node'=>$node)); ?>
</div>
<div id="right">
	<?php Nn::partial('admin'.DS.'_logo') ?>
	<?php Nn::partial('admin'.DS.'_stats') ?>
</div>