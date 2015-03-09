<?php $videoID = $text->content() ?>
<div id="BG" class="BG image">
	<object width="750" height="550">
		<param name="allowfullscreen" value="true" />
		<param name="allowscriptaccess" value="always" />
		<param name="wmode" value="opaque" />
		<param name="movie" value="http://vimeo.com/moogaloop.swf?clip_id=<?php echo $videoID ?>&amp;server=vimeo.com&amp;show_title=1&amp;show_byline=1&amp;show_portrait=0&amp;color=EEEEEE&amp;fullscreen=1&amp;autoplay=1&amp;loop=0" />
		<embed src="http://vimeo.com/moogaloop.swf?clip_id=<?php echo $videoID ?>&amp;server=vimeo.com&amp;show_title=1&amp;show_byline=1&amp;show_portrait=0&amp;color=EEEEEE&amp;fullscreen=1&amp;autoplay=1&amp;loop=0" type="application/x-shockwave-flash" allowfullscreen="true" allowscriptaccess="always" wmode="opaque" width="750" height="550">
		</embed>
	</object>
</div>