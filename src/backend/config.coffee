exports.config =

	modules:
		definition: false
		wrapper: false

	files:
		javascripts:
			joinTo:
				'js/vendor.js': /^bower_components/
				'js/main.js': /^app\/scripts/
			order:
				after: [
					'app/scripts/main.coffee'
				]

		stylesheets:
			joinTo:
				'css/main.css': /^(bower_components|app\/styles)/

		templates:
			joinTo: 'js/dontUseMe'

	paths:
		public: '../../public/backnn'

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
			plugins: ['autoprefixer-stylus']

	overrides:
		production:
			optimize: false
			sourceMaps: false
			plugins:
				autoReload:
					enabled: false