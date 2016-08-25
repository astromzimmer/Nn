<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<html>

  <head>

    <meta name="robots" content="noindex, nofollow" />

    <title> <?php echo Nn::settings('PAGE_NAME') ?> </title>
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo Nn::settings('DOMAIN'); ?>/favicon.ico" />

    <?php
      $style_files = [
        'backnn/css/main.css'
      ];
      echo Nn::minify()->cssTags($style_files);
    ?>
	
	<base href="<?php echo Nn::settings('DOMAIN'); ?>"></base>

  </head>

  <body id="body">
    <?php Nn::partial('Def','_flash') ?>
  	<div id="page">