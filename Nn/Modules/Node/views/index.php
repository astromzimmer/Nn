<div id="left">
	<?php Nn::partial('Node','_tree',array('nodes'=>$nodes,'node'=>$node)); ?>
</div>
<div id="right">
	<?php Nn::partial('Admin','_logo') ?>
	<?php Nn::partial('Admin','_stats') ?>
</div>