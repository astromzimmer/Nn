<div id="text<?php
	if($text->attr('id')) echo htmlentities($text->attr('id'));
?>-content" class="text aMD <?php
	if($text->attr('id')) echo str_replace(' ', '_', strtolower($text->attributetype()->attr('name')));
?>" data-id="<?php
	if($text->attr('id')) echo $text->attr('id');
?>"><?php
	if($text->attr('id')) echo $text->content();
?></div>