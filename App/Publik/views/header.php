<!DOCTYPE html>

<html lang="en">

  <head>
  	
  	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0"/>
  	
  	<!-- g.wm.tools verifier -->

    <base href="<?php echo DOMAIN ?>" target="_self" />

    <title> <?php echo PAGE_NAME ?> </title>

    <link type="text/css" rel="stylesheet" href="http://fast.fonts.net/cssapi/64090cdd-7ac0-42e7-ab46-76a838c0dc87.css"/>
    <?php
      $platform = (Utils::is_mobile()) ? 'mobile' : 'desktop';
      $files = [
        'css/main.css',
      ];
      echo Nn::minify()->cssTags($files,'concat_'.$platform.'.css');
    ?>

    <script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

      ga('create', 'UA-22992675-1', 'astromzimmer.com');
      ga('send', 'pageview');

    </script>

  </head>

  <body id="body" class="<?php echo $root ?>">