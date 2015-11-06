this.appSpace = {}

(($,Dispatcher,Router,App)->

	App.dispatcher = new Dispatcher()
	App.router = Router()

	if document.domain is "localhost"
		window.domain = $("base").attr("href")
	else
		window.domain = "http://" + document.domain

	$(document).ready ->

		$document = $(document)
		$left = $('#left')
		$flash = $('#flash')

		$flash.on 'click', ->
			$this = $(this)
			$this.hide()
		.delay(2400).fadeOut()

		# Retrieve #left scroll position
		_left_scroll = parseInt($.cookie('left_scroll'))

		# Init code editor
		$code_editor = $('#code-editor')
		if $code_editor.length > 0
			code_editor = ace.edit 'code-editor'
			code_editor.setTheme 'ace/theme/monokai'
			code_editor.setShowPrintMargin false
			code_editor.getSession().setMode 'ace/mode/php'
			if $code_editor.hasClass 'read-only'
				code_editor.setReadOnly true

		# Retrieve tree menu state
		$tree = $('.tree')
		if $tree.length > 0
			$branches = $('.tree li')
			tree_type = $tree.attr 'id'
			expanded_branches_raw = $.cookie('expanded_'+tree_type)
			expanded_branches = if expanded_branches_raw then JSON.parse(expanded_branches_raw) else []
			# console.log $.cookie('expanded_'+tree_type)
			$branches.each ()->
				$this = $(this)
				id = $this.data('id')
				if id in expanded_branches
					expandBranch $this,0
					setTimeout ->
						$left[0].scrollTop = _left_scroll
					,200

			$("ul li .expander").click ->
				$this = $(this)
				$li = $this.closest('li')
				$children = $this.siblings('ul')
				if $li.hasClass("expanded")
					collapseBranch $li
				else
					expandBranch $li
				# Persist node menu state
				expanded_branches = []
				$branches.each ()->
					$this = $(this)
					if $this.hasClass('expanded')
						expanded_branches.push $this.data('id')
				$.cookie 'domain',domain
				$.cookie 'path',window.location.pathname
				$.cookie 'expanded_'+tree_type,JSON.stringify(expanded_branches)
				# console.log $.cookie()
				false

		$left.on 'scroll', ->
			$this = $(this)
			clearTimeout $this.data('scrollTimer')
			$this.data 'scrollTimer', setTimeout ->
				$.cookie 'domain',domain
				$.cookie 'path',window.location.pathname
				$.cookie 'left_scroll',$this[0].scrollTop
			,250

		# Unshift menu
		$('.unshifter').on 'click', (e)->
			$this = $(this)
			$shifted

		$('.video[data-id]').each ()->
			$this = $(this)
			video_id = $this.data 'id'
			$iframe = $('<iframe id="player1" src="//player.vimeo.com/video/'+video_id+'?api=1&player_id=player1" width="630" height="354" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>')
			$this.append $iframe
			player = aVIMEO($iframe[0])
			player.addEvent 'ready', ()->
				$this.on 'click', ->
					player.api 'play'


		$document.on 'click',".visibility_toggle", (e)->
			e.preventDefault()
			$this = $(this)
			visible = if $this.hasClass('visible') then 0 else 1
			id = $this.data 'target_id'
			collection = $this.data 'target_collection'
			$.ajax
				url: "/admin/" + collection + "/toggle"
				type: "POST"
				data: "id=" + id + "&visible=" + visible
				dataType: "text"
				complete: (feedback) ->
					$this.toggleClass('visible')
			return false

		.on 'dblclick', '.attribute:not(.editing)', (e)->
			$this = $(this)
			attribute_id = $this.data 'id'
			App.dispatcher.trigger 'attribute:edit', attribute_id

		# Trash warning
		.on 'click', 'a.trash', (e)->
			false unless confirm("Are you sure you want to delete this element?")

		.on 'click', 'ul li .expander', (e)->
			$this = $(this)
			$li = $this.closest('li')
			$children = $this.siblings('ul')
			if $li.hasClass("expanded")
				collapseBranch $li
			else
				expandBranch $li
			# Persist node menu state
			expanded_branches = []
			$branches.each ()->
				$this = $(this)
				if $this.hasClass('expanded')
					expanded_branches.push $this.data('id')

		.on 'click','.collapse',(e)->
			e.preventDefault()
			$this = $(this)
			$attributes = $this.closest('.attribute')
			if $attributes.length > 0
				$attributes.toggleClass('collapsed')
			else
				$('.attributes').toggleClass('collapsed')
			return false

		.on 'click', '#admin .menu li.active a', (e)->
			e.preventDefault()
			$this = $(this)
			$('#admin').toggleClass 'expanded'
			return false

		.on 'mouseleave', '#admin', ()->
			$this = $(this)
			$this.removeClass 'expanded'

		.on 'mouseenter', "[data-tooltip]", (e)->
			$this = $(this)
			# console.log $this.attr("alt")
			$("#hint").show().text $this.data("tooltip")

		.on 'mouseleave', "[data-tooltip]", (e)->
			$this = $(this)
			$("#hint").hide().text ""

		.on 'click', '#right .header:not(.maximised) .title', (e)->
			$('#right .manage').scrollTop 0

		.on 'mousedown', 'ul.sortable', (e) ->
			e.stopPropagation()

		.on 'mouseover', 'ul.sortable', (e)->
			$this = $(this)
			$this.sortable
				handle: '.handle'
				axis: "y"
				update: (e,ui) ->
					$itm = $(ui.item)
					doSort $itm

		$('#right .manage').on 'scroll', (e)->
			$header = $('#right .header')
			scroll_top = this.scrollTop
			if scroll_top > 48
				$header.removeClass('maximised')
			else
				$header.addClass('maximised')

		$("select#datatypeField").change (e) ->
			$this = $(this)
			$("#paramsContainer").html ''
			$option = $('option:selected',$this)
			url_param = $option.data 'url_param'
			if url_param
				$.ajax
					url: '/admin/attributetypes/_params/'+url_param
					success: (response)->
						console.log response
						$("#paramsContainer").html response

		$("textarea.md").aMD
			imgPath: "/backnn/imgs/static/aMD"
			refEndpoint: '/api/nodes'
			extStyles: [
				"/backnn/css/fonts.css"
				"/backnn/css/editor.css"
			]
			icons: true

	expandBranch = ($n,t)->
		# duration = t or 100
		# $indicator = $n.children('.expander')
		# $submenu = $n.children('ul')
		$n.addClass 'expanded'
		# $indicator.addClass 'expanded'
		# $submenu.slideDown duration

	collapseBranch = ($n,t)->
		# duration = t or 100
		# $indicator = $n.children('.expander')
		# $submenu = $n.children('ul')
		$n.removeClass 'expanded'
		# $indicator.removeClass 'expanded'
		# $submenu.slideUp duration

	doSort = ($itm)->
		$this = $itm
		$ul = $this.closest 'ul.sortable'
		id = $ul.attr('id')
		model = id.replace(/\w*_/, "")
		parent = id.replace(/_\w*/, "")
		mKey = model + "[]"
		$.ajax
			url: "/admin/" + model + "/sort"
			type: "POST"
			data: "parent_id=" + parent + "&" + $ul.sortable("serialize",
				key: mKey
			)
			dataType: "script"
			complete: (feedback) ->
				console.log "Sorted OK!"

)(jQuery,Dispatcher,Router,this.appSpace)