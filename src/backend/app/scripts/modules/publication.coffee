((App)->

	Publication = ->

		_id = null
		_print = null

		$publication = $('#publication')
		$cart = $('#cart')

		init = ->
			#

		exists = ->
			$publication.length > 0

		getId = ->
			$IDpub = $('#publication[data-id]')
			if $IDpub then return parseInt($IDpub.data 'id')
			return false

		render = ->
			if not _print
				console.log 'initialising print'
				_print = aPRINT '#publication .publication',
					format: 'A3'
					editable: false
					styles: '/backnn/css/print.css'

				_print.on 'loaded', ->
					alert 'loaded!'
					$publication.removeClass 'loading'
				_print.on 'scroll', focusCart
			else
				_print.frameResize()
				setTimeout ->
					_print.frameResize()
				,200

		fetch = (publication_id,section_id)->
			if(publication_id)
				$publication.addClass 'loading'
				path = '/admin/publications/view/'+publication_id
				if section_id then path += '/'+section_id
				$.ajax
					url: path+'?render_as=partial'
					dataType: 'html'
					success: (result,status,jqXHR)->
						_id = publication_id
						$publication[0].dataset.id = _id
						$publication[0].innerHTML = result
						_print = null
						render()
						setTimeout ->
							_print.frameResize()
							scrollToSection section_id
							$publication.removeClass 'loading'
						,200

		fetchCart = (publication_id)->
			$cart.addClass 'loading'
			$.ajax
				url: '/admin/publications/cart/'+publication_id+'?render_as=partial'
				success: (result,status,jqXHR)->
					$cart.html result
					$cart.removeClass 'loading'

		fetchList = (id)->
			url = '/admin/api/nodes/tree'
			if id then url += '/'+id
			$.ajax
				url: url+'?render_as=partial'
				success: (response)->
					_nodeList = response
					containers.nodes.innerHTML = _nodeList
					focusOn id

		focusCart = (section_id)->
			if(section_id)
				# HACK!
				$publication.removeClass 'loading'
				# /HACK
				$sections = $('#right li.section')
				if $sections.length is 0 then return fetchCart()
				$('#right li.section').removeClass 'focus active'
				$('#right li.section[data-id="'+section_id+'"]').addClass 'focus active'

		scrollToSection = (section_id)->
			_print.scrollTo 'section[data-id="'+section_id+'"]'

		setSection = (node_id,bubbling)->
			data =
				node_id: node_id
				bubbling: bubbling
			$.ajax
				url: '/admin/publications/set/1?render_as=partial'
				type: 'PUT'
				data: data
				dataType: 'html'
				success: (response)->
					if $('#section_'+node_id).length is 0
						$cart[0].innerHTML = response
					_id = 1
					fetch _id
					App.dispatcher.trigger 'publication:update', 1

		removeSection = (node_id)->
			$.ajax
				url: '/admin/publications/remove/1?render_as=partial'
				type: 'PUT'
				data:
					node_id: node_id
				dataType: 'html'
				success: (response)->
					console.log 'Response:',response
					_id = 1
					$cart[0].innerHTML = response
					fetch _id
					App.dispatcher.trigger 'publication:update', _id

		print = ->
			if _print then _print.print()

		return {
			exists: exists
			render: render
			fetch: fetch
			fetchCart: fetchCart
			focusCart: focusCart
			scrollToSection: scrollToSection
			setSection: setSection
			removeSection: removeSection
			print: print
			id: getId
		}
	
	App.publication = new Publication()

)(this.appSpace)