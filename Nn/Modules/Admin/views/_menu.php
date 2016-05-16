<div class="main menu">
	<ul>
		<li class="main btn<?php if(str_replace('Controller','',Nn::getCurrentController()) == 'Nodes') echo ' active"' ?>">
			<a href="<?php echo '/admin/nodes' ?>"><?php echo Nn::babel('Nodes') ?></a>
		</li>
		<?php if(Nn::authenticated('admins')): ?>
		<li class="super btn<?php if(str_replace('Controller','',Nn::getCurrentController()) == 'Nodetypes') echo ' active"' ?>">
			<a href="<?php echo DOMAIN.DS,'admin/nodetypes' ?>"><?php echo Nn::babel('Nodetypes') ?></a>
		</li>
		<li class="super btn<?php if(str_replace('Controller','',Nn::getCurrentController()) == 'Attributetypes') echo ' active"' ?>">
			<a href="<?php echo '/admin/attributetypes' ?>"><?php echo Nn::babel('Attributetypes') ?></a>
		</li>
		<li class="super btn<?php if(str_replace('Controller','',Nn::getCurrentController()) == 'Settings') echo ' active"' ?>">
			<a href="<?php echo '/admin/settings' ?>"><?php echo Nn::babel('Settings') ?></a>
		</li>
		<li class="super btn<?php if(str_replace('Controller','',Nn::getCurrentController()) == 'Files') echo ' active"' ?>">
			<a href="<?php echo '/admin/files' ?>"><?php echo Nn::babel('Files') ?></a>
		</li>
		<?php endif; ?>
		<li class="btn<?php if(str_replace('Controller','',Nn::getCurrentController()) == 'Forms') echo ' active"' ?>">
			<a href="<?php echo '/admin/forms' ?>"><?php echo Nn::babel('Forms') ?></a>
		</li>
		<li class="btn<?php if(str_replace('Controller','',Nn::getCurrentController()) == 'Users') echo ' active"' ?>">
			<a href="<?php echo '/admin/users' ?>"><?php echo Nn::babel('Users') ?></a>
		</li>
		<li>
			<a class="btn" href="<?php echo '/admin/backup_db' ?>"><?php echo Nn::babel('Backup db') ?></a>
		</li>
		<li>
			<a class="btn" href="<?php echo '/admin/flush_cache' ?>"><?php echo Nn::babel('Flush cache') ?></a>
		</li>
		<li>
			<a class="btn" href="<?php echo '/admin/logout' ?>"><?php echo Nn::babel('Log out') ?></a>
		</li>
	</ul>
</div>