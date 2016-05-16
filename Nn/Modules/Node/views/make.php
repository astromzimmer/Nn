<div id="left">
	<?php Nn::partial('Node','_tree',array('nodes'=>$nodes,'node'=>$node)); ?>
</div>
<div id="center">
	<?php Nn::partial('Node','_make',array('node'=>$node,'nodetypes'=>$nodetypes)); ?>
</div>
<div id="right"></div>