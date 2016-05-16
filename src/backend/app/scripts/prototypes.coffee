scrollTo = (el,target,duration)->
	if typeof el is 'string' then el = document.querySelector el
	if typeof target is 'string' then target = document.querySelector target
	el.style.position = 'relative'
	start = el.scrollTop
	change = if target then target.offsetTop - start else 0 - start
	console.log 'Start:',start
	console.log 'Change:',change
	currentTime = 0
	increment = 20
	animate = ->
		currentTime += increment
		scrollTop = Math.easeInOutQuad currentTime,start,change,duration
		el.scrollTop = scrollTop
		if currentTime < duration
			setTimeout animate, increment

	animate()

isVisible = (el,parent_rect)->
	if not parent_rect then parent_rect =
		top: 0
		bottom: window.innerHeight
	el_rect = el.getBoundingClientRect()
	invisible = (el_rect.bottom is 0 and el_rect.top is 0) or el_rect.bottom < parent_rect.top or el_rect.top > parent_rect.bottom
	return not invisible

Math.easeInOutQuad = (ct,s,c,d)->
	ct /= d/2
	if ct < 1 then return c/2*ct*ct + s
	ct--
	return -c/2 * (ct*(ct-2) - 1) + s

String.prototype.toDate = ->
	d = new Date parseInt(this)*1000
	d.getDate()+'.'+(d.getMonth()+1)+'.'+d.getFullYear()

String.prototype.escape = ->
	# this.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;')
	encodeURIComponent this

String.prototype.activeURLs = ->
	Autolinker.link this

String.prototype.vertical = ->
	this.split('').join('\n\b')

String.prototype.pml = ->
	result = ''
	_array = this.split(',')
	for char in _array
		result += String.fromCharCode parseInt(char)
	return result