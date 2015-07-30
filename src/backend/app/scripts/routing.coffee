$(document).ready ->

	if history and history.pushState

		$doc = $(document)
		$left = $('#left')
		$right = $('#right')

		origin = window.location.origin

		focusTree = (focused_id)->
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
					$left.html result
					if callback then callback()

		fireAMD = ->
			$("textarea.md").aMD
				imgPath: "/backnn/imgs/static/aMD"
				extStyles: [
					"/backnn/css/fonts.css"
					"/backnn/css/editor.css"
				]
				icons: true

		R = Router()
		R.add /admin\/nodes\/view\/(\d+)$/, (path,node_id)->
			$.ajax
				url: '/'+path+'?render_as=partial'
				success: (result,status,jqXHR)->
					$right.html result
					focusTree node_id
		R.add /admin\/nodes\/make$/, (path)->
			$.ajax
				url: '/'+path+'?render_as=partial'
				success: (result,status,jqXHR)->
					$right.html result
					$right.html(result).find('input:text').first().focus()
		R.add /admin\/nodes\/make\/in\/(\d+)$/, (path,parent_id)->
			$.ajax
				url: '/'+path+'?render_as=partial'
				success: (result,status,jqXHR)->
					$('li.node').removeClass 'focus active'
					$('li.node[data-id='+parent_id+']').addClass('active').parents('li').addClass 'active'
					$right.html(result).find('input:text').first().focus()
		R.add /admin\/nodes\/edit\/(\d+)$/, (path,node_id)->
			$.ajax
				url: '/'+path+'?render_as=partial'
				success: (result,status,jqXHR)->
					$right.html result
		R.add /admin\/nodes\/delete\/(\d+)$/, (path,node_id)->
			$.ajax
				url: '/'+path+'?render_as=partial'
				success: (result,status,jqXHR)->
					$right.html result
					$('li.node[data-id='+node_id+']').remove()
					success_path = jqXHR.getResponseHeader('Redirect')
					R.navigate '/'+success_path
		R.add /admin\/nodes\/view\/(\d+)\/(\w+)$/, (path,node_id,attribute_type)->
			$.ajax
				url: '/admin/attributes/make/'+attribute_type+'/in/'+node_id+'?render_as=partial'
				success: (result,status,jqXHR)->
					$('.maker',$right).html result
					fireAMD()
		R.add /admin\/nodes\/view\/(\d+)\/(\w+)\/(\d+)$/, (path,node_id,attribute_type,attribute_id)->
			$.ajax
				url: '/admin/attributes/edit/'+attribute_id+'?render_as=partial'
				success: (result,status,jqXHR)->
					$('#attribute_'+attribute_id).replaceWith result
					fireAMD()
		R.add /admin\/attributes\/delete\/(\d+)$/, (path,attribute_id)->
			$.ajax
				url: '/'+path+'?render_as=partial'
				success: (result,status,jqXHR)->
					success_path = jqXHR.getResponseHeader('Redirect')
					R.navigate '/'+success_path
		R.start()

		$doc.on 'click', '[data-ajax]', (e)->
			$this = $(this)
			e.preventDefault()
			path = $this.attr('href').replace(origin,'')
			R.navigate path
			# return false

		$doc.on 'click', 'li.node .label', ->
			$this = $(this)
			$('li.node').removeClass 'focus active'
			$this.closest('li').addClass('focus').parents('li').addClass 'active'

		$doc.on 'submit', 'form[data-target]', (e)->
			e.preventDefault()
			$this = $(this)
			target = $this.data 'target'
			$target = $('#'+target)
			url = $this.attr('action')+'?render_as=partial'
			data = new FormData(this)
			$.ajax
				url: url
				method: 'POST'
				data: data
				processData: false
				contentType: false
				success: (result,status,jqXHR)->
					success_path = jqXHR.getResponseHeader('Redirect')
					if success_path then R.navigate '/'+success_path+window.location.hash
					$target.html result
			return false