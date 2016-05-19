<div class="borderline">
	<div id="roles" class="menu tree">
		<ul >
		<?php if($roles): ?>
		<?php foreach($roles as $r): ?>
			<li class="role<?php if(isset($role) && $r == $role) echo ' active expanded'; if(isset($focus) && $r == $focus) echo ' focus' ?>" id="role_<?php echo $r->attr('id') ?>" data-id="<?php echo $r->attr('id') ?>">
				<div class="grouper">
					<div class="expander"></div>
					<div class="label">
						<a href="<?php echo DOMAIN.'/admin/users/manage_role/'.$r->attr('id') ?>"><?php echo $r->attr('name') ?></a>
					</div>
				</div>
				<ul id="0_users" class="submenu">
					<?php if($users = $r->users()): ?>
					<?php foreach($users as $u): ?>
					<li class="user<?php if(isset($focus) && $u == $focus) echo ' focus' ?>" id="user_<?php echo $u->attr('id') ?>">
						<div class="grouper">
							<div class="label">
								<a href="<?php echo DOMAIN,DS,'admin',DS,'users',DS,'manage',DS,$u->attr('id') ?>"><?php echo $u->full_name(); ?></a>
							</div>
						</div>
					</li>
					<?php endforeach ?>
					<?php endif; ?>
					<li>
						<div class="add"><a href="<?php echo DOMAIN.'/admin/users/manage/in/'.$r->attr('id') ?>"><?php echo Utils::UIIcon('plus'); ?></a></div>
					</li>
				</ul>
			</li>
		<?php endforeach ?>
		<?php endif; ?>
		<?php if(Nn::authenticated('super')): ?>
			<li>
				<div class="add">
					<a href="<?php echo DOMAIN.'/admin/users/manage_role' ?>"><?php echo Utils::UIIcon('plus'); ?></a>
				</div>
			</li>
		<?php endif; ?>
		</ul>
	</div>
</div>