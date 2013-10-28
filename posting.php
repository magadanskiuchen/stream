<form class="posting" action="" method="post">
	<div class="field textarea-container">
		<textarea name="stream_content"></textarea>
	</div>
	
	<div class="field input-container">
		<input type="text" name="stream_title" placeholder="<?php echo esc_attr( date( stream_datetime_format() ) ); ?>" />
	</div>
	
	<div class="field submit-container">
		<input type="submit" name="stream_submit" value="<?php esc_attr_e('Post', 'stream'); ?>" />
	</div>
</form>