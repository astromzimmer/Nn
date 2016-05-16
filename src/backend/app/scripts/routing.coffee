((App)->

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
					refEndpoint: '/api/nodes'
					extStyles: [
						"/backnn/css/fonts.css"
						"/backnn/css/editor.css"
					]
					icons: true
				$('textarea, input').focus()
				$('input[name=content]').select()

			App.router.add /admin\/nodes\/view\/(\d+)$/, (path,node_id)->
				$right.addClass 'loading'
				$.ajax
					url: '/'+path+'?render_as=partial'
					success: (result,status,jqXHR)->
						$right.html result
						focusTree node_id
						$right.removeClass 'loading'
			App.router.add /admin\/nodes\/make$/, (path)->
				$right.addClass 'loading'
				$.ajax
					url: '/'+path+'?render_as=partial'
					success: (result,status,jqXHR)->
						$right.html result
						$right.html(result).find('input:text').first().focus()
						$right.removeClass 'loading'
			App.router.add /admin\/nodes\/make\/in\/(\d+)$/, (path,parent_id)->
				$right.addClass 'loading'
				$.ajax
					url: '/'+path+'?render_as=partial'
					success: (result,status,jqXHR)->
						$('li.node').removeClass 'focus active'
						$('li.node[data-id='+parent_id+']').addClass('active').parents('li').addClass 'active'
						$right.html(result).find('input:text').first().focus()
						$right.removeClass 'loading'
			App.router.add /admin\/nodes\/edit\/(\d+)$/, (path,node_id)->
				$right.addClass 'loading'
				$.ajax
					url: '/'+path+'?render_as=partial'
					success: (result,status,jqXHR)->
						$right.html result
						$right.removeClass 'loading'
			App.router.add /admin\/nodes\/delete\/(\d+)$/, (path,node_id)->
				$right.addClass 'loading'
				$.ajax
					url: '/'+path+'?render_as=partial'
					success: (result,status,jqXHR)->
						$right.html result
						$('li.node[data-id='+node_id+']').remove()
						success_path = jqXHR.getResponseHeader('Redirect')
						App.router.navigate '/'+success_path
			App.router.add /admin\/nodes\/view\/(\d+)\/(\w+)$/, (path,node_id,attribute_type)->
				$right.addClass 'loading'
				$.ajax
					url: '/admin/attributes/make/'+attribute_type+'/in/'+node_id+'?render_as=partial'
					success: (result,status,jqXHR)->
						$('.maker',$right).html result
						fireAMD()
						$right.removeClass 'loading'
			App.router.add /admin\/nodes\/view\/(\d+)\/(\w+)\/(\d+)$/, (path,node_id,attribute_type,attribute_id)->
				$('#attribute_'+attribute_id).addClass 'loading'
				$.ajax
					url: '/admin/attributes/edit/'+attribute_id+'?render_as=partial'
					success: (result,status,jqXHR)->
						$('#attribute_'+attribute_id).replaceWith result
						fireAMD()
			App.router.add /admin\/attributes\/delete\/(\d+)$/, (path,attribute_id)->
				$right.addClass 'loading'
				$.ajax
					url: '/'+path+'?render_as=partial'
					success: (result,status,jqXHR)->
						success_path = jqXHR.getResponseHeader('Redirect')
						App.router.navigate '/'+success_path

			App.router.add /admin\/feeds\/delete_post\/(\d+)$/, (path,post_id)->
				$.ajax
					url: '/'+path+'?render_as=partial'
					success: (result,status,jqXHR)->
						success_path = jqXHR.getResponseHeader('Redirect')
						App.router.navigate '/'+success_path
			
			App.router.start()

			$doc.on 'click', '[data-ajax]', (e)->
				$this = $(this)
				e.preventDefault()
				path = $this.attr('href').replace(origin,'')
				App.router.navigate path
				# return false

			$doc.on 'click', 'li.node .label', ->
				$this = $(this)
				$('li.node').removeClass 'focus active'
				$this.closest('li').addClass('focus').parents('li').addClass 'active'

			$doc.on 'submit', 'form[data-target]', (e)->
				e.preventDefault()
				$this = $(this)
				$this.addClass 'loading'
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
						if success_path then App.router.navigate '/'+success_path+window.location.hash
						$this.removeClass 'loading'
						$target.html result
				return false

)(this.appSpace)