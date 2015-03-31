<?php if($art_children = $art->children()) { ?>
  <div id="art" class="roll">
    <?php foreach($art_children as $art_child): ?>
      <div id="<?php echo $art_child->attr('id') ?>" class="page">
        <?php foreach($art_child->attributes() as $attribute): ?>
          <?php
            Nn::partial($attribute->public_view(),array(strtolower($attribute->datatype())=>$attribute->data()))
          ?>
        <?php endforeach; ?>
      </div>
    <?php endforeach; ?>
  </div>
<?php } ?>
<?php if($app_children = $app->children()) { ?>
  <div id="app" class="roll">
    <?php foreach($app_children as $app_child): ?>
      <div id="<?php echo $app_child->attr('id') ?>" class="page">
        <?php if($slideshows = $app_child->children_by_type('Slideshow')): ?>
          <?php foreach($slideshows as $slideshow): ?>
            <?php
              Nn::partial('Publik','_slideshow',array('slideshow'=>$slideshow))
            ?>
          <?php endforeach; ?>
        <?php elseif($attributes = $app_child->attributes()): ?>
          <?php foreach($attributes as $attribute): ?>
            <?php
              Nn::partial($attribute->public_view(),array(strtolower($attribute->datatype())=>$attribute->data()))
            ?>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>
  </div>
<?php } ?>
<div id="top">
  <a class="root art" href="/art" data-state="art">
    <div class="word">Art</div>
  </a>
  <a class="root app" href="/app" data-state="app">
    <div class="word">App</div>
  </a>
</div>
<?php if($art_children) { ?>
  <div class="left menu">
    <?php foreach($art_children as $art_child): ?>
      <div class="item">
        <div class="btn" data-art-target="<?php echo $art_child->attr('id') ?>"><?php echo $art_child->title() ?></div>
      </div>
    <?php endforeach; ?>
  </div>
<?php } ?>
<?php if($app_children) { ?>
  <div class="right menu">
    <?php foreach($app_children as $app_child): ?>
      <div class="item">
        <div class="btn" data-app-target="<?php echo $app_child->attr('id') ?>"><?php echo $app_child->title() ?></div>
      </div>
    <?php endforeach; ?>
  </div>
<?php } ?>
<?php if($logo) { ?>
  <div id="logo">
    <a href="<?php echo DOMAIN ?>" data-state="index">
      <?php
        echo $logo->data()->tag(124)
      ?>
    </a>
  </div>
<?php } ?>