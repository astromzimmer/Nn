((App)->

	$(document).ready ->

		if history and history.pushState

			App.$doc = $(document)
			App.$page = $('#page')
			App.$left = $('#left')
			App.$center = $('#page > #center')
			App.$right = $('#page > #right')
			App.$node = $('#page > #center > #node')

			origin = window.location.origin

			App.setMode = (mode)->
				if mode
					App.$page.addClass(mode)
					App.dispatcher.trigger 'mode:'+mode

			App.unsetMode = (mode)->
				if mode
					App.$page.removeClass(mode)
				else
					App.$page.removeClass()

			App.toggleMode = (mode)->
				if mode
					App.$page.toggleClass mode

			App.fireScrollWatcher = ->
				$('#center, #admin_node').on 'scroll', (e)->
					$this = $(this)
					$this.data 'scrollTimer', setTimeout ->
						$header = $('#page #center #node .header')
						scroll_top = $this.scrollTop()
						if scroll_top > 48
							$header.removeClass('maximised')
						else
							$header.addClass('maximised')
					,24

			fireAMD = ->
				$("textarea.md").aMD
					imgPath: "/backnn/imgs/static/aMD"
					refEndpoint: '/api/search'
					extStyles: [
						"/backnn/css/fonts.css"
						"/backnn/css/editor.css"
					]
					icons: true
				$('textarea:first, input:first').focus()
				$('input[name=content]').select()

			# App.navigate = (path)->
			# 	cash = window.location.pathname.split(':')[1]
			# 	if cash then path = path.split(':')[0] + ':' + cash
			# 	App.router.navigate path

			App.router.add /admin\/publications\/view\/(\d+)(\/(\d+))?(:(\w+))?$/, (path,publication_id,s,section_id,m,mode)->
				path = path.replace m, ''
				App.unsetMode()
				App.setMode 'publication'
				App.nodes.focusTree 0
				console.log 'publication_id:',App.publication.id()
				if App.publication.id() isnt parseInt(publication_id)
					App.publication.fetchCart publication_id
					App.publication.fetch publication_id, section_id
				else
					App.publication.render()
					App.publication.focusCart section_id
					App.publication.scrollToSection section_id

			App.router.add /admin\/nodes\/(view|layout)\/(\d+)(:(\w+))?$/, (path,view,node_id,m,mode)->
				base_path = path.replace m, ''
				previous_path = App.router.previousPath().split(':')[0]
				App.unsetMode 'publication'
				App.setMode mode
				if base_path is previous_path
					if path.indexOf('layout') isnt -1 then App.nodes.firePrint node_id
					App.nodes.focusTree node_id
				else
					App.nodes.fetch path, node_id
					App.setMode mode
					App.publication.focusCart()

			App.router.add /admin\/nodes\/make(:(\w+))?$/, (path)->
				App.$center.addClass 'loading'
				App.unsetMode 'publication'
				$.ajax
					url: '/'+path+'?render_as=partial'
					success: (result,status,jqXHR)->
						App.$center.html(result).find('input:text').first().focus()
						App.$center.removeClass 'loading'

			App.router.add /admin\/nodes\/make\/in\/(\d+)(:(\w+))?$/, (path,parent_id)->
				App.$center.addClass 'loading'
				App.unsetMode 'publication'
				$.ajax
					url: '/'+path+'?render_as=partial'
					success: (result,status,jqXHR)->
						$('li.node').removeClass 'focus active'
						$('li.node[data-id='+parent_id+']').addClass('active').parents('li').addClass 'active'
						App.$center.html(result).find('input:text').first().focus()
						App.$center.removeClass 'loading'

			App.router.add /admin\/nodes\/edit\/(\d+)(:(\w+))?$/, (path,node_id)->
				App.$center.addClass 'loading'
				App.unsetMode 'publication'
				$.ajax
					url: '/'+path+'?render_as=partial'
					success: (result,status,jqXHR)->
						App.$center.html result
						App.$center.find('input')[0].focus()
						App.$center.removeClass 'loading'

			App.router.add /admin\/nodes\/delete\/(\d+)$/, (path,node_id)->
				App.$center.addClass 'loading'
				$.ajax
					url: '/'+path+'?render_as=partial'
					success: (result,status,jqXHR)->
						App.$center.html result
						$('li.node[data-id='+node_id+']').remove()
						success_path = jqXHR.getResponseHeader('Redirect')
						App.$center.removeClass 'loading'
						App.router.navigate '/'+success_path

			App.router.add /admin\/nodes\/(view|layout)\/(\d+)\/(\w+)(:(\w+))?$/, (path,view,node_id,attribute_type,m,mode)->
				path = path.replace m, ''
				App.$node.addClass 'loading'
				App.unsetMode 'publication'
				App.unsetMode 'layout'
				$.ajax
					url: '/admin/attributes/make/'+attribute_type+'/in/'+node_id+'?render_as=partial'
					success: (result,status,jqXHR)->
						$('.maker',App.$center).html result
						fireAMD()
						App.fireScrollWatcher()
						App.$node.removeClass 'loading'

			App.router.add /admin\/nodes\/(view|layout)\/(\d+)\/(\w+)\/(\d+)(:(\w+))?$/, (path,view,node_id,attribute_type,attribute_id,m,mode)->
				path = path.replace m, ''
				App.unsetMode 'publication'
				App.unsetMode 'layout'
				$('#attribute_'+attribute_id).addClass 'loading'
				$.ajax
					url: '/admin/attributes/edit/'+attribute_id+'?render_as=partial'
					success: (result,status,jqXHR)->
						$('#attribute_'+attribute_id).replaceWith result
						fireAMD()
						App.fireScrollWatcher()

			App.router.add /admin\/attributes\/delete\/(\d+)$/, (path,attribute_id)->
				App.$center.addClass 'loading'
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

			App.$doc.on 'click', '[data-ajax]', (e)->
				$this = $(this)
				e.preventDefault()
				path = $this.attr('href').replace(origin,'')
				App.router.navigate path
				# return false

			App.$doc.on 'click', 'a[href]', (e)->
				$this = $(this)
				href = $this.attr('href')
				if href.indexOf(':') is 0
					e.preventDefault()
					base_path = window.location.pathname.split(':')[0]
					# console.log base_path
					App.router.navigate base_path + href
					return false

			App.$doc.on 'click', 'li.node .label', ->
				$this = $(this)
				$('li.node').removeClass 'focus active'
				$this.closest('li').addClass('focus').parents('li').addClass 'active'

			App.$doc.on 'click', '#right .toggle', ->
				App.setMode 'cart'

			App.$doc.on 'click', '#node', ->
				App.unsetMode 'cart'

			App.$doc.on 'click', '#section .toggle', ->
				App.toggleMode 'layout'

			App.$doc.on 'click', '#publication .print', ->
				App.publication.print()

			App.$doc.on 'click', '#section .reset', (e)->
				sure = confirm 'Are you sure you want to reset the layout?'
				e.preventDefault()
				if sure
					$this = $(this)
					window.location = $this.attr('href')
				return false

			App.$doc.on 'submit', 'form[data-target]', (e)->
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
						# $this.removeClass 'loading'
						# $target.html result
				return false

			App.$doc.on 'click', '#left .node .pub', (e)->
				$this = $(this)
				node_id = $this.closest('.node').data('id')
				App.publication.setSection node_id

			App.$doc.on 'click', '#right #cart .section .remove', (e)->
				$this = $(this)
				node_id = $this.data('id')
				App.publication.removeSection node_id

			App.$doc.on 'change', '#right #cart .section .bubble :checkbox', ->
				console.log 'hejhej'
				$this = $(this)
				node_id = $this.closest('.bubble').data 'id'
				console.log 'Checked: ',$this[0].checked
				if $this[0].checked
					App.publication.setSection node_id, 1
				else
					App.publication.setSection node_id, 0

			App.$doc.on 'keydown', (e)->
				if e.metaKey
					switch e.keyCode
						when 69
							e.preventDefault()
							node_id = $('#center .node').data('id')
							App.router.navigate('/admin/nodes/edit/'+node_id)
				if e.shiftKey
					# Shift
					$handle = $('#section .handle')
					$handle.addClass 'shift'

			App.$doc.on 'keyup', (e)->
				if e.shiftKey
					# Shift
					$handle = $('#section .handle')
					$handle.removeClass 'shift'

			fireAMD()
			App.fireScrollWatcher()

			App.router.start()

)(this.appSpace)