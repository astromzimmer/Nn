<div id="left">
	<?php Nn::partial('Nodetype'.DS.'_list',array('nodetypes'=>$nodetypes)) ?>
</div>
<div id="center">
	<div class="view">
		<div id="<?php echo htmlentities($image->id); ?>" class="service">
			<div><h3><?php echo htmlentities($image->alt); ?></h3></div>
			<img alt="<?php echo $image->alt; ?>" src="<?php echo Nn::settings('DOMAIN').'/admin/assets/Image/'.$image->id.'/'.$image->filename; ?>" />
			<br/>
			<br/>
			<div class="edit">
				<a href="<?php echo Nn::settings('DOMAIN').'/admin/assets/Image/'.$image->id.'/'.$image->filename; ?>"><?php echo UIIcon("paper-clip2"); ?></a>
				<a href="<?php echo Nn::settings('DOMAIN').'/admin/images/delete/'.$image->id ?>"><?php echo UIIcon("trash"); ?></a>
			</div>
			<div class="meta">
				<div>size: <?php echo htmlentities($image->size); ?></div>
			</div>
		</div>
	</div>
</div>