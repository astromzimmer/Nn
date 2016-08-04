((App)->

	$(document).ready ->

		App.dispatcher.on 'nodetypes:sorted', (data)->
			$.ajax
				url: "/admin/nodetypes/sort"
				type: "POST"
				data: data
				dataType: "script"
				complete: (feedback) ->
					console.log "Sorted OK!"

		App.dispatcher.on 'attributetypes:sorted', (data)->
			$.ajax
				url: "/admin/attributetypes/sort"
				type: "POST"
				data: data
				dataType: "script"
				complete: (feedback) ->
					console.log "Sorted OK!"

		App.dispatcher.on 'nodes:sorted', (data)->
			$.ajax
				url: "/admin/nodes/sort"
				type: "POST"
				data: data
				dataType: "script"
				complete: (feedback) ->
					console.log "Sorted OK!"

		App.dispatcher.on 'attributes:sorted', (data)->
			$.ajax
				url: "/admin/attributes/sort"
				type: "POST"
				data: data
				dataType: "script"
				complete: (feedback) ->
					console.log "Sorted OK!"

		App.dispatcher.on 'sections:sorted', (data)->
			$.ajax
				url: "/admin/publications/sort"
				type: "POST"
				data: data
				dataType: "script"
				complete: (feedback) ->
					console.log "Sorted OK!"

		App.dispatcher.on 'attribute:edit', (attribute_id)->
			$attribute = $('#attribute_'+attribute_id)
			edit_URI = $attribute.find('.edit').attr 'href'
			App.router.navigate edit_URI

		App.dispatcher.on 'publication:update', (publication_id)->
			#

		# App.dispatcher.on 'mode:publication', ->
		# 	App.publication.render()

)(this.appSpace)