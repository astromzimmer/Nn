<!-- <?php
	#$visitor_count = Nn::report();
?>
<div id="stats">
	<div class="radial_progress">
		<div class="circle">
			<div class="mask left">
				<div class="fill"></div>
			</div>
			<div class="mask right">
				<div class="fill"></div>
				<div class="fill fix"></div>
			</div>
		</div>
	</div>
	<div class="count">
		<div class="number">
			<?php #print_r($visitor_count) ?>
		</div>
		&nbsp;&nbsp;<?php echo Nn::babel('Unique visitors') ?> *
	</div>
</div>
<div class="stats_link">
<?php
	#$third_party_string = (Nn::settings('ANALYTICS')) ? 
	#	'Visit Google Analytics for more precise info' :
	#	'Use Google Analytics for more precise info'
?>
	<a href="http://google.com/analytics/">* <?php #echo Nn::babel($third_party_string) ?>.</a>
</div> -->