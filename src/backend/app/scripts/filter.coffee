((App)->

	$(document).ready ->

		$doc = $(document)

		$doc.on 'keyup', 'input#filter', (e)->
			$that = $(this)
			$nodes = $('li.node')
			value = $that.val().toLowerCase()
			$nodes.hide().removeClass('active expanded')
			if value.length > 0
				$nodes.filter (index)->
					this.dataset.label.toLowerCase().indexOf(value) isnt -1
				.show().addClass('active').parents('li.node').show().addClass('active expanded')
			else
				$nodes.show()
				$('li.node.focus').parents('li.node').addClass 'active expanded'

)(this.appSpace)