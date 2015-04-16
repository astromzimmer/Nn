<?php if($params): ?>
	<?php foreach($params as $key => $value): ?>
		<?php if (is_array($value)): ?>
			<select name="params[<?php echo $key ?>]" class="formfield" id="<?php echo $key ?>Field" >
				<?php foreach ($value as $key => $value): ?>
					<option value="<?php echo $value ?>"><?php echo Nn::babel(ucwords($value)) ?></option>
				<?php endforeach ?>
			</select>			
		<?php endif ?>
	<?php endforeach; ?>
<?php endif ?>