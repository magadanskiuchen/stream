<form class="posting" action="" method="post">
	<textarea name="stream_content"></textarea>
	<input name="stream_title" value="<?php echo esc_attr( date( stream_datetime_format() ) ); ?>" />
	
	<input name="stream_submit" type="submit" value="<?php esc_attr_e('Post', 'stream'); ?>" />
</form>