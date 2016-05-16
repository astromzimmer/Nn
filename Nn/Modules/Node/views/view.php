<div id="left">
	<?php Nn::partial('Node','_tree',array('nodes'=>$nodes,'node'=>$node)); ?>
</div>
<div id="center">
	<?php Nn::partial('Node','_view',array('node'=>$node,'dtype'=>$dtype,'atype'=>$atype,'attr_id'=>$attr_id)); ?>
</div>
<div id="right"></div>