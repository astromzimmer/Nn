<div id="left">
	<?php Nn::partial('Node','_tree',array('nodes'=>$nodes,'node'=>$node)); ?>
</div>
<div id="center">
	<?php Nn::partial('Node','_edit',array('node'=>$node,'nodetypes'=>$nodetypes,'parents'=>$parents)); ?>
</div>
<div id="right"></div>