$(document).ready ->

	if document.domain is "localhost"
		window.domain = $("base").attr("href")
	else
	  	window.domain = "http://" + document.domain

	$document = $(document)
	$left = $('#left')
	$flash = $('#flash')

	$flash.on 'click', ->
		$this = $(this)
		$this.hide()
	.delay(2400).fadeOut()

	# Init code editor
	$code_editor = $('#code-editor')
	if $code_editor.length > 0
		code_editor = ace.edit 'code-editor'
		code_editor.setTheme 'ace/theme/monokai'
		code_editor.setShowPrintMargin false
		code_editor.getSession().setMode 'ace/mode/php'
		if $code_editor.hasClass 'read-only'
			code_editor.setReadOnly true

	
	# Trash warning
	$("a.trash").bind "click", ->
		false unless confirm("Are you sure you want to delete this element?")

	# Retrieve tree menu state
	$tree = $('.tree')
	if $tree.length > 0
		$branches = $('.tree li')
		tree_type = $tree.attr 'id'
		expanded_branches_raw = $.cookie('expanded_'+tree_type)
		expanded_branches = if expanded_branches_raw then JSON.parse($.cookie('expanded_'+tree_type)) else []
		# console.log $.cookie('expanded_'+tree_type)
		$branches.each ()->
			$this = $(this)
			id = $this.data('id')
			if id in expanded_branches
				expandBranch $this,0

		$("ul li .expander").click ->
			$this = $(this)
			$li = $this.closest('li')
			$children = $this.siblings('ul.submenu')
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
			$.cookie 'domain','.'+domain
			$.cookie 'path','/'
			$.cookie 'expanded_'+tree_type,JSON.stringify(expanded_branches)
			# console.log $.cookie()
			false

	$("#left ul.sortable").sortable
		connectWith: '#left ul.sortable'
		handle: '.handle'
		axis: "y"
		update: (e,ui) ->
			$itm = $(ui.item)
			doSort $itm

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

	$('#right .manage').on 'scroll', (e)->
		$header = $('#right .header')
		scroll_top = this.scrollTop
		if scroll_top > 48
			$header.removeClass('maximised')
		else
			$header.addClass('maximised')

	$("ul.sortable").bind "mousedown", (e) ->
		e.stopPropagation()

	$("#right ul.sortable").sortable
		handle: '.handle'
		axis: "y"
		update: (e,ui) ->
			$itm = $(ui.item)
			doSort $itm

	$("select#datatypeField").change (e) ->
		$this = $(this)
		$("#optionsContainer").html ''
		$option = $('option:selected',$this)
		url_param = $option.data 'url_param'
		if url_param
			$.ajax
				url: '/admin/'+url_param+'/_options'
				success: (response)->
					console.log response
					$("#optionsContainer").html response

	$("textarea.md").aMD
		imgPath: "/backnn/imgs/static/aMD"
		extStyles: [
			"/backnn/css/fonts.css"
			"/backnn/css/editor.css"
		]
		icons: true

expandBranch = ($n,t)->
	duration = t or 100
	$indicator = $n.children('.expander')
	$submenu = $n.children('.submenu')
	$n.addClass 'expanded'
	$indicator.addClass 'expanded'
	$submenu.slideDown duration

collapseBranch = ($n,t)->
	duration = t or 100
	$indicator = $n.children('.expander')
	$submenu = $n.children('.submenu')
	$n.removeClass 'expanded'
	$indicator.removeClass 'expanded'
	$submenu.slideUp duration

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