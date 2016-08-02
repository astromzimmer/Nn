<!DOCTYPE html>

<html lang="en">

  <head>
  	
    <?php if (Nn::settings('DEVELOPMENT_ENV')): ?>
      <meta name="robots" content="noindex,nofollow">
    <?php endif ?>

  	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- <base target="_blank" /> -->

    <title> <?php echo Nn::settings('PAGE_NAME') ?> </title>

    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>
    <?php
      $files = [
        'css/main.css'
      ];
      echo Minify::cssTags($files,'concat_public.css');
    ?>
  </head>

  <body class="<?php if(!$is_root && !isset($service_id)) echo 'no_banner' ?>">