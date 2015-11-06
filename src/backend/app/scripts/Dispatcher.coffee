
Dispatcher = ->
	this._events = {}
	return @

Dispatcher.prototype.on = (message,callback)->
	(this._events[message] || (this._events[message] = [])).push callback

Dispatcher.prototype.trigger = (evt)->
	args = Array.prototype.slice.call arguments, 1
	if not callbacks = this._events[evt] then return
	i = callbacks.length
	while i--
		callbacks[i].apply this, args

