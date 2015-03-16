<?php if($flashes_array = Nn::flash()): ?>
<div id="flash">
	<?php foreach($flashes_array as $flashes): ?>
		<?php foreach($flashes as $type => $message): ?>
			<div class="message<?php if(!empty($type)) echo ' '.$type; ?>">
			<?php if(!empty($message)) echo $message; ?>
			</div>
		<?php endforeach; ?>
	<?php endforeach; ?>
</div>
<?php endif; ?>