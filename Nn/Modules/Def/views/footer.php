	</div>
	<div id="footer"></div>
	<?php
		$js_files = [
			'backnn/js/vendor.js',
			'backnn/js/main.js'
		];
		echo Nn::minify()->jsTags($js_files,'backnn/js/concat_admin.js');
	?>
  </body>
</html>