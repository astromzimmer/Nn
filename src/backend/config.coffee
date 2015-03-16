exports.config =

	modules:
		definition: false
		wrapper: false

	conventions:
		ignored = (path)->
			not /^(.*\.md)$/.test(path) and not startsWith(sysPath.basename(path),'_')

	files:
		javascripts:
			joinTo:
				'js/backnn-vendor.js': /^bower_components/
				'js/backnn.js': /^app\/scripts/
			order:
				after: [
					'app/scripts/main.coffee'
				]

		stylesheets:
			joinTo:
				'css/backnn.css': /^(bower_components|app\/styles)/

		templates:
			joinTo: 'js/dontUseMe'

	paths:
		public: '../../public'

	sourceMaps: false

	plugins:
		uglify:
			mangle: false
			minify: false
		autoReload:
			host: 'localhost'
			enabled:
				css: on
				js: on
				assets: on
		stylus:
			includeCss: true

	overrides:
		production:
			optimize: false
			sourceMaps: false
			plugins:
				autoReload:
					enabled: false