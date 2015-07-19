<div id="left">
	<?php Nn::partial('Node','_tree',array('nodes'=>$nodes,'node'=>$node)); ?>
</div>
<div id="right">
	<?php Nn::partial('Node','_view',array('node'=>$node,'dtype'=>$dtype,'atype'=>$atype,'edit_attribute_id'=>$edit_attribute_id)); ?>
</div>