	<? if(Utils::is_mobile()): ?>
		<?php
			$js_files = [
				'js/public-vendor.js',
				'js/public.js'
			];
			echo Nn::minify()->jsTags($js_files,'concat_public-mobile.js');
		?>
	<? else: ?>
		<?php
			$js_files = [
				'js/public-vendor.js',
				'js/public.js'
			];
			echo Nn::minify()->jsTags($js_files,'concat_public-desktop.js');
		?>
	<? endif; ?>

  </body>
</html>

<?php if(isset($db)) { $db=null; } ?>