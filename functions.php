<?php
define('STREAM_VERSION', '1.0');

add_action('after_setup_theme', 'stream_after_setup_theme');

function stream_after_setup_theme() {
	# i18n
	load_theme_textdomain('stream', 'lang');
	
	# Add filters
	add_filter('wp_title', 'stream_wp_title', 10, 2);
	add_filter('get_avatar', 'stream_retina_avatar', 10, 5);
	
	# Add actions
	add_action('wp_enqueue_scripts', 'stream_wp_enqueue_scripts');
	add_action('admin_enqueue_scripts', 'stream_admin_enqueue_scripts');
}

function stream_wp_title($title, $sep) {
	global $paged, $page;
	
	if (is_feed()) return $title;
	
	$title .= get_bloginfo('name');
	$site_description = get_bloginfo('description', 'display');
	
	if ($site_description && (is_home() || is_front_page())) $title = "$title $sep $site_description";
	
	if ($paged >= 2 || $page >= 2) $title = $title . ' ' . $sep . ' ' . sprintf(__('Page %s', 'stream'), max($paged, $page));
	
	return $title;
}

function stream_wp_enqueue_scripts() {
	# Enqueue styles
	wp_enqueue_style('stream', get_bloginfo('template_directory') . '/style.css', array(), STREAM_VERSION, 'all');
	
	# Enqueue scripts
	wp_enqueue_script('jquery');
	wp_enqueue_script('stream-functions', get_bloginfo('template_directory') . '/js/func.js', array('jquery', 'stream-support'), STREAM_VERSION);
}

function stream_admin_enqueue_scripts() {
	
}

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
?>