<div id="left">
	<?php Nn::partial('Node','_tree',array('nodes'=>$nodes,'node'=>$node)); ?>
</div>
<div id="center">
	<?php Nn::partial('Node','_layout',array('node'=>$node,'dtype'=>$dtype,'atype'=>$atype,'attr_id'=>$attr_id,'section'=>$section,'page_cls'=>$page_cls)); ?>
</div>
<div id="right">
	<div id="publication"></div>
	<div id="cart">
		<?php Nn::partial('Publication','_cart',['publication'=>$publication]); ?>
	</div>
	<div class="toggle"></div>
</div>