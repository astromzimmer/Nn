<?php Nn::partial('Node','_view',array('node'=>$node,'dtype'=>$dtype,'atype'=>$atype,'attr_id'=>$attr_id)); ?>
<?php if($section): ?>
<div id="section">
	<?php Nn::partial('Publication','_section',array('section'=>$section)); ?>
</div>
<?php endif ?>
<!-- <div class="navigation">
	<a class="item content<?php if($page_cls == 'content') echo ' active' ?>" href=":content"><?php echo Nn::babel('Content') ?></a>
	<a class="item layout<?php if($page_cls == 'layout') echo ' active' ?>" href=":layout"><?php echo Nn::babel('Layout') ?></a>
</div> -->