<div id="files" class="admin_area menu tree">
	<ul id="0_files" class="sortable">
		<?php foreach($files as $key => $f): ?>
			<?php Nn::partial('files'.DS.'_list',array('key'=>$key,'file'=>$f)); ?>
		<?php endforeach ?>
		<!-- <li>
			<span class="add"><a href="<?php echo DOMAIN.DS.'admin'.DS.'files'.DS.'make' ?>"><?php Utils::UIIcon('plus'); ?></a></span>
		</li> -->
	</ul>
</div>