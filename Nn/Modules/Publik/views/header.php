<!DOCTYPE html>

<html lang="en">

  <head>
  	
    <meta name="robots" content="noindex,nofollow">

  	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- <base target="_blank" /> -->

    <title> <?php echo PAGE_NAME ?> </title>

    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>
    <?php
      $files = [
        'css/public.css'
      ];
      echo Minify::cssTags($files,'concat_public.css');
    ?>
    <?php
      $js_files = [
        'js/public-head.js'
      ];
      echo Nn::minify()->jsTags($js_files,'concat_public_head.js');
    ?>
  </head>

  <body class="<?php if(!$is_root && !isset($service_id)) echo 'no_banner' ?>">