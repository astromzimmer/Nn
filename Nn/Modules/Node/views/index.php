<div id="left">
	<?php Nn::partial('Node','_tree',['nodes'=>$nodes,'node'=>$node]); ?>
</div>
<div id="center">
	<?php Nn::partial('Admin','_logo') ?>
</div>
<div id="right">
	<div id="publication"></div>
	<div id="cart">
		<?php Nn::partial('Publication','_cart'); ?>
	</div>
	<div class="toggle"></div>
</div>