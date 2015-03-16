	</div>
	<div id="footer">
		<div id="hint"></div>
	</div>

	<?php
		$js_files = [
			'js/backnn-vendor.js',
			'js/backnn.js'
		];
		echo Nn::minify()->jsTags($js_files,'concat_admin.js');
	?>
  </body>
</html>