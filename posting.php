<?php
if (current_user_can('publish_posts')) {
	?>
	<form class="posting" action="" method="post">
		<div class="field textarea-container">
			<textarea name="stream_content" autofocus></textarea>
		</div>
		
		<div class="field input-container">
			<input type="text" name="stream_title" placeholder="<?php echo esc_attr( date( stream_datetime_format(), current_time('timestamp') ) ); ?>" />
		</div>
		
		<div class="field submit-container">
			<input type="submit" name="stream_submit" value="<?php esc_attr_e('Post', 'stream'); ?>" />
		</div>
	</form>
	<?php
}
?>