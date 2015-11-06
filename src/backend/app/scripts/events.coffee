((App)->

	$(document).ready ->

		App.dispatcher.on 'attribute:edit', (attribute_id)->
			$attribute = $('#attribute_'+attribute_id)
			edit_URI = $attribute.find('.edit').attr 'href'
			App.router.navigate edit_URI

)(this.appSpace)