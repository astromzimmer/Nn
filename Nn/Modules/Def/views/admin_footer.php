	</div>
	<div id="footer">
		<div id="hint"></div>
	</div>

	<?php
		$js_files = [
			'backnn/js/vendor.js',
			'backnn/js/main.js'
		];
		echo Nn::minify()->jsTags($js_files,'concat_admin.js');
	?>
  </body>
</html>