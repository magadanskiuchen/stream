<?php
function stream_datetime_format() {
	$format = get_option('time_format') . ', ' . get_option('date_format');
	
	apply_filters('stream_datetime_format', $format);
	
	return $format;
}

function stream_retina_avatar($avatar, $id_or_email, $size, $default, $alt) {
	$retina_size = absint($size) * 2;
	
	$avatar = preg_replace('/\?s=' . preg_quote($size) . '/', '?s=' . $retina_size, $avatar);
	
	return $avatar;
}

add_filter('get_avatar', 'stream_retina_avatar', 10, 5);
?>