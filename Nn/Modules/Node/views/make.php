<div id="left">
	<?php Nn::partial('Node','_tree',array('nodes'=>$nodes,'node'=>$node)); ?>
</div>
<div id="right">
	<?php Nn::partial('Node','_make',array('node'=>$node,'nodetypes'=>$nodetypes)); ?>
</div>