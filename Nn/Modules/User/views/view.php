<div id="left">
	<?php Nn::partial('User'.DS.'_tree',array('roles'=>$roles,'role'=>$user->role())) ?>
</div>
<div id="center">
	<div class="manage">
		<div id="<?php echo htmlentities($user->id); ?>" class>
			<div>
				<?php echo Nn::babel('First name') ?>:
				<b><?php echo htmlentities($user->first_name); ?></b>
			</div>
			<div>
				<?php echo Nn::babel('Last name') ?>:
				<b><?php echo htmlentities($user->last_name); ?></b>
			</div>
			<div>
				<?php echo Nn::babel('Email') ?>:
				<b><?php echo htmlentities($user->email); ?></b>
			</div>
			<div class="tools">
				<a class="edit" href="<?php echo DOMAIN.DS.'admin'.DS.'users'.DS.'manage'.DS.$user->attr('id') ?>"><?php Utils::UIIcon('edit'); ?></a>
				<a class="trash" href="<?php echo DOMAIN.DS.'admin'.DS.'users'.DS.'delete'.DS.$user->attr('id') ?>"><?php Utils::UIIcon('trash'); ?></a>
			</div>
		</div>
	</div>
</div>