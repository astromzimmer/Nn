((App)->

	Nodes = ->

		_node_id = null
		_print = null

		getNode = (path,node_id)->
			App.$center.addClass 'loading'
			$.ajax
				url: '/'+path+'?render_as=partial'
				dataType: 'html'
				success: (result,status,jqXHR)->
					App.$center[0].innerHTML = result
					App.fireScrollWatcher()
					if path.indexOf('layout')
						_print = null
						firePrint node_id
					focusTree node_id
					App.$center.removeClass 'loading'

		focusTree = (focused_id)->
			_node_id = focused_id
			$focused_node = $('li.node[data-id='+focused_id+']')
			if $focused_node.length is 0 then return reloadTree focused_id
			$('li.node').removeClass 'focus active'
			$focused_node.closest('li').addClass('focus').parents('li').addClass 'active'

		reloadTree = (focused_id,callback)->
			if not focused_id
				$focused_node = $('.node.focus')
				if $focused_node.length is 0 then $focused_node = $('.node.active').last()
				focused_id = if $focused_node.length isnt 0 then $focused_node.data('id') else ''
			$.ajax
				url: '/admin/nodes/tree/'+focused_id+'?render_as=partial'
				success: (result)->
					App.$left.html result
					if callback then callback()

		firePrint = (node_id)->
			if not _print or node_id isnt _node_id
				$section = $('#center .section section')
				settings =
					format: 'A4'
					styles: '/backnn/css/print.css'
				settings.template = $section.data 'template'
				data_rules = $section.data 'rules'
				settings.rules = if data_rules.rules then data_rules.rules else data_rules
				_print = aPRINT '#section .section', settings
				if _print
					_print.on 'update', ->
						node_id = $('#node .node').data 'id'
						data =
							markup: _print.get()
						$.ajax
							url: '/admin/nodes/layout/'+node_id
							method: 'post'
							data: data
							success: (response)->
								#
			setTimeout ->
				_print.frameResize()
			,200

		return {
			focusTree: focusTree
			firePrint: firePrint
			fetch: getNode
		}
	
	App.nodes = new Nodes()

)(this.appSpace)