<div class="borderline">
	<div id="files" class="admin_area menu tree">
		<ul>
			<?php foreach($files as $key => $f): ?>
				<?php Nn::partial('file','_list',array('key'=>$key,'file'=>$f)); ?>
			<?php endforeach ?>
			<!-- <li>
				<span class="add"><a href="<?php echo Nn::s('DOMAIN').'/admin/files/make' ?>"><?php Utils::UIIcon('plus'); ?></a></span>
			</li> -->
		</ul>
	</div>
</div>