<?php $first = ($is_root) ? true : false ?>
<div id="banners" class="<?php if($first) echo 'slideshow' ?>">
	<?php foreach($services as $service): ?>
	<?php if($banner = $service->attribute('banner')): ?>
	<div class="service banner<?php if($first || (isset($service_id) && $service->attr('id') == $service_id)) echo ' active' ?>" data-service="<?php echo $service->attr('id') ?>">
		<img src="<?php echo $banner->data()->src(1280) ?>" alt="Complementa">
	</div>
	<?php endif; ?>
	<?php $first = false ?>
	<?php endforeach; ?>
</div>
<div id="page">
	<div id="services">
		<ul class="menu">
			<?php $first = ($is_root) ? true : false ?>
			<?php foreach($services as $service): ?>
			<li class="service item<?php if($first || (isset($service_id) && $service->attr('id') == $service_id)) echo ' active' ?>" data-service="<?php echo $service->attr('id') ?>">
				<a href="<?php echo '/'.$language->attr('title').'/'.$service->slug() ?>"><?php echo $service->attr('title') ?></a>
			</li>
			<?php $first = false ?>
			<?php endforeach; ?>
		</ul>
	</div>
	<ul class="left menu">
		<?php if(isset($service_id)): ?>
		<?php foreach($services as $service): ?>
		<?php if($service->attr('id') == $service_id && $children = $service->children()): ?>
		<?php foreach($children as $child): ?>
		<li class="item<?php if(isset($page) && ($page->attr('id') == $child->attr('id') || in_array($child, $page->navigation_tree()))) echo ' active' ?>">
			<a href="<?php echo '/'.$language->attr('title').'/'.$child->slug() ?>"><?php echo $child->attr('title') ?></a>
		</li>
		<?php endforeach; ?>
		<?php endif ?>
		<?php endforeach; ?>
		<?php else: ?>
		<?php foreach($top_items as $p): ?>
		<?php if(isset($page) && ($p->attr('id') == $page->attr('id') || in_array($p,$page->navigation_tree()))): ?>
		<?php if($children = $p->children()): ?>
		<?php foreach($children as $child): ?>
		<li class="item<?php if(isset($page) && ($page->attr('id') == $child->attr('id') || in_array($child, $page->navigation_tree()))) echo ' active' ?>">
			<a href="<?php echo '/'.$language->attr('title').'/'.$child->slug() ?>"><?php echo $child->attr('title') ?></a>
		</li>
		<?php endforeach; ?>
		<?php endif ?>
		<?php endif ?>
		<?php endforeach; ?>
		<?php endif ?>
	</ul>
	<div id="content">
		<?php if(isset($page)): ?>
		<?php if($attributes = $page->attributes_except('banner')): ?>
			<?php foreach($attributes as $attribute): ?>
				<?php Nn::partial($attribute->public_view(),array(strtolower($attribute->datatype())=>$attribute->data())); ?>
			<?php endforeach; ?>
		<?php endif; ?>
		<?php endif; ?>
	</div>
	<div id="footer">
		<ul class="menu">
			<?php foreach($top_items as $p): ?>
			<li class="item<?php if(isset($page) && ($page->attr('id') == $p->attr('id') || in_array($p, $page->navigation_tree()))) echo ' active' ?>">
				<div class="header"><a href="<?php echo '/'.$language->attr('title').'/'.$p->slug() ?>"><?php echo $p->attr('title') ?></a></div>
				<?php if($children = $p->children()): ?>
				<ul class="sub menu">
					<?php foreach($children as $child): ?>
					<li class="item<?php if(isset($page) && ($page->attr('id') == $child->attr('id') || in_array($child, $page->navigation_tree()))) echo ' active' ?>">
						<a href="<?php echo '/'.$language->attr('title').'/'.$child->slug() ?>"><?php echo $child->attr('title') ?></a>
					</li>
					<?php endforeach; ?>
				</ul>
				<?php endif; ?>
			</li>
			<?php endforeach; ?>
		</ul>
	</div>
</div>
<div id="header">
	<div id="logo">
		<?php if($image = $logo->data()): ?>
		<a href="/<?php echo $language->attr('title') ?>"><img src="<?php echo $image->src() ?>" alt="Complementa"></a>
		<?php endif; ?>
	</div>
	<ul class="top menu">
		<?php foreach($top_items as $p): ?>
		<?php $is_subbed = ($children = $p->children()) ? true : false ?>
		<li class="item<?php if($is_subbed) echo ' subbed' ?><?php if(isset($page) && ($page->attr('id') == $p->attr('id') || in_array($p, $page->navigation_tree()))) echo ' active' ?>">
			<a href="<?php echo '/'.$language->attr('title').'/'.$p->slug() ?>"><?php echo $p->attr('title') ?></a>
			<?php if($is_subbed): ?>
			<ul class="sub menu">
				<?php foreach($children as $child): ?>
				<li class="item">
					<a href="<?php echo '/'.$language->attr('title').'/'.$child->slug() ?>"><?php echo $child->attr('title') ?></a>
				</li>
				<?php endforeach; ?>
			</ul>
			<?php endif; ?>
		</li>
		<?php endforeach; ?>
	</ul>
	<div id="search">
		<form action=""></form>
	</div>
</div>