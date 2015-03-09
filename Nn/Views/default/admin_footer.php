	</div>
	<div id="footer">
		<div id="hint"></div>
	</div>

	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
	<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>
	<?php
		$js_files = [
			'js/vendor/jquery/jquery.cookie.js',
			'js/vendor/jquery/jquery.selection.js',
			'js/vendor/Markdown.Converter.js',
			'js/vendor/aMD.Converter.js',
			'js/vendor/jquery/jquery.aMD.js',
			'js/vendor/ace/ace.js',
			'js/vendor/ace/mode-php.js',
			'js/vendor/ace/theme-monokai.js',
			'js/main.js',
			'js/admin.js'
		];
		echo Nn::minify()->jsTags($js_files,'concat_admin.js');
	?>
  </body>
</html>

<?php if(isset($db)) { $db=null; } ?>