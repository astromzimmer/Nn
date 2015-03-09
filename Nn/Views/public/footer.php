	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
	<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>
	<? if(Utils::is_mobile()): ?>
		<?php
			$js_files = [
				'/js/vendor/jquery/jquery.aGALLERY.js',
				'/js/vendor/Hyphenator/Hyphenator.js',
				'/js/vendor/Hyphenator/patterns/en-gb.js',
				'/js/public-mobile.js'
			];
			echo Minify::jsTags($js_files,'concat_public-mobile.js');
		?>
	<? else: ?>
		<?php
			$js_files = [
				// '/js/vendor/jquery/jquery.resizeend.js',
				// '/js/vendor/jquery/jquery.aGALLERY.js',
				// '/js/vendor/Hyphenator/Hyphenator.js',
				// '/js/vendor/Hyphenator/patterns/en-gb.js',
				'/js/public-desktop.js'
			];
			echo Minify::jsTags($js_files,'concat_public-desktop.js');
		?>
	<? endif; ?>

  </body>
</html>

<?php if(isset($db)) { $db=null; } ?>