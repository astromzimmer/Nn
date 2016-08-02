<div id="feeds">
<?php if(isset($service) && $service == 'done'): ?>
	<div class="info">
		You've authorised <?php echo Nn::settings('PAGE_NAME') ?> successfully!
	</div>
<?php elseif(isset($feed) && $feed): ?>
	<div class="info">
		Please press the button below to allow <?php echo Nn::settings('PAGE_NAME') ?> to access public data for <code><?php echo $feed->attr('handle') ?></code> on <?php echo $service ?>.
	</div>
	<form id="authorise" method="POST">
		<button type="submit">Submit</button>
	</form>
<?php endif ?>
</div>