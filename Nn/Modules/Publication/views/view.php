<div id="left">
	<?php Nn::partial('Node','_tree',['nodes'=>$nodes]); ?>
</div>
<div id="center"></div>
<div id="right">
	<div id="publication" class="loading" data-id="<?php echo $publication->attr('id') ?>">
		<?php Nn::partial('Publication','_view',['publication'=>$publication]); ?>
	</div>
	<div id="cart">
		<?php Nn::partial('Publication','_cart',['publication'=>$publication]); ?>
	</div>
	<div class="toggle"></div>
</div>