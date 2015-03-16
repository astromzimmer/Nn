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
				'js/admin-vendor.js': /^backend\/bower_components/
				'js/admin.js': /^backend\/app\/scripts/
				'js/public-vendor.js': /^frontend\/bower_components/
				'js/public.js': /^frontend\/app\/scripts/
			order:
				after: [
					'backend/app/scripts/admin.coffee'
					'frontend/app/scripts/public-desktop.coffee'
					'frontend/app/scripts/public-mobile.coffee'
				]

		stylesheets:
			joinTo:
				'css/admin.css': /^backend\/(bower_components|app\/styles)/
				'css/public.css': /^frontend\/(bower_components|app\/styles)/

		templates:
			joinTo: 'js/dontUseMe'

	paths:
		watched: ['backend','frontend']
		public: 'public'

	sourceMaps: false

	plugins:
		# jade_angular:
		# 	single_file: true
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

	server:
		port: 3456

	overrides:
		production:
			optimize: false
			sourceMaps: false
			# paths:
			# 	public: 'public-production'
			plugins:
				autoReload:
					enabled: false