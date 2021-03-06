<!doctype html>
  <!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en" ng-app="app"> <![endif]-->
  <!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8 ie7" lang="en" ng-app="app"> <![endif]-->
  <!--[if IE 8]>    <html class="no-js lt-ie9 ie8" lang="en" ng-app="app"> <![endif]-->
  <!--[if (gte IE 8)|!(IE)]><!--><html class="no-js" lang="en" ng-app="avocado"> <!--<![endif]-->
  <head>
  	
    <meta name="robots" content="noindex, nofollow" />

    <!-- CHROME AND VIEWPORT SETTINGS -->
    <meta http-equiv="Content-type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta http-equiv="imagetoolbar" content="no">
    <meta name="viewport" content="width=device-width, initial-scale=1"/>

    <title> <?php echo Nn::s('PAGE_NAME') ?> - admin </title>
    
    <link href='//fonts.googleapis.com/css?family=Roboto:400,100,300,700,900|Average|Hind:300,400,500,600,700' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css">
    <?php
      $style_files = [
        'backnn/css/main.css'
      ];
      echo Nn::minify()->cssTags($style_files,'backnn/css/concat_backnn.css');
    ?>
  	<base href="<?php echo Nn::s('DOMAIN'); ?>"></base>
	
  </head>

  <body id="body" class="<?php if(Nn::s('PRINT')) echo 'print ' ?>">
    <?php Nn::partial('Def','_flash') ?>
  	<div class="BG">
  		<!-- <img src="<?php echo Nn::s('DOMAIN'),'/imgs/static/admin_bg.jpg' ?>" /> -->
  	</div>
    
  	<div id="admin" class="muli<?php if(isset($index)) echo ' root expanded' ?>">
      <?php if(Nn::authenticated()) Nn::partial('Admin','_menu') ?>
      <div class="impressum">
        <a href="http://astromzimmer.com" target="_blank"><?php echo Nn::babel('Created by') ?> A/Z</a>
      </div>
  		<div class="feedback"></div>
  	</div>
  	
    <?php if(!isset($page_cls)) $page_cls = '' ?>
  	<div id="page" class="<?php echo $page_cls ?>">