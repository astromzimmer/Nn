	<? if(Utils::is_mobile()): ?>
		<?php
			$js_files = [
				'/js/public.js'
			];
			echo Minify::jsTags($js_files,'concat_public-mobile.js');
		?>
	<? else: ?>
		<?php
			$js_files = [
				'/js/public.js'
			];
			echo Minify::jsTags($js_files,'concat_public-desktop.js');
		?>
	<? endif; ?>

  </body>
</html>

<?php if(isset($db)) { $db=null; } ?>