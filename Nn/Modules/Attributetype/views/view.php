<div id="left">
	<?php Nn::partial('Attributetype''_list',array('attributetypes'=>$attributetypes)) ?>
</div>
<div id="center">
	<div class="view">
		<div id="<?php echo htmlentities($image->id); ?>" class="service">
			<div><h3><?php echo htmlentities($image->alt); ?></h3></div>
			<img alt="<?php echo $image->alt; ?>" src="<?php echo DOMAIN,DS,'admin',DS,'assets',DS,'Image',DS,$image->id,DS,$image->filename; ?>" />
			<br/>
			<br/>
			<div class="tools">
				<a href="<?php echo DOMAIN,DS,'admin',DS,'assets',DS,'Image',DS,$image->id,DS,$image->filename; ?>"><?php echo UIIcon("paper-clip2"); ?></a>
				<a class="trash" href="<?php echo DOMAIN,DS,'admin',DS,'images',DS,'delete',DS,$image->id ?>"><?php echo UIIcon("trash"); ?></a>
			</div>
			<div class="meta">
				<div>size: <?php echo htmlentities($image->size); ?></div>
			</div>
		</div>
	</div>
</div>