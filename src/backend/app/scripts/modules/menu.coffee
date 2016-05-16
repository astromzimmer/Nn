((App)->

	Menu = ->

		containers =
			menu: document.querySelector '[data-area="menu"]'

		_menu = null

		init = ->
			#

		select = (item)->
			if not _menu
				$.ajax
					url: '/admin/api/menu/'+item+'?render_as=partial'
					success: (response)->
						_menu = response
						containers.menu.innerHTML = _menu
						focusOn item
			else
				focusOn item

		focusOn = (item)->
			$('.btn',containers.menu).removeClass 'focus'
			$('.btn[data-item="'+item+'"]',containers.menu).addClass 'focus'

		init()

		return {
			select: select
		}
	
	App.menu = new Menu()

)(this.appSpace)