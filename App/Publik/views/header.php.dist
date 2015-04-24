<!DOCTYPE html>

<html lang="en">

  <head>
  	
  	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0"/>
  	
  	<!-- g.wm.tools verifier -->

    <base target="_blank" />

    <title> <?php echo PAGE_NAME ?> </title>

    <link href='http://fonts.googleapis.com/css?family=Roboto:400,100,300,700,900' rel='stylesheet' type='text/css'>
    <?php
      $platform = (Utils::is_mobile()) ? 'mobile' : 'desktop';
      $files = [
        'css/public.css',
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

  <body id="body" class="">