<?php
define('STREAM_VERSION', '1.0');

add_action('after_setup_theme', 'stream_after_setup_theme');

function stream_after_setup_theme() {
	# i18n
	load_theme_textdomain('stream', 'lang');
	
	# Add filters
	add_filter('wp_title', 'stream_wp_title', 10, 2);
	add_filter('get_avatar', 'stream_retina_avatar', 10, 5);
	add_filter('the_title', 'stream_the_title');
	
	# Add actions
	add_action('init', 'stream_submit_post');
	add_action('init', 'stream_js_action');
	add_action('wp_enqueue_scripts', 'stream_wp_enqueue_scripts');
	add_action('admin_enqueue_scripts', 'stream_admin_enqueue_scripts');
	add_action('pre_get_posts', 'stream_pre_get_posts');
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
	wp_enqueue_script('stream-functions', get_bloginfo('template_directory') . '/js/func.js', array('jquery'), STREAM_VERSION);
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

function stream_submit_post() {
	if ($_SERVER['REQUEST_METHOD'] == 'POST' && !is_admin() && isset($_POST['stream_content'])) {
		$p = array();
		$p['post_type'] = 'post';
		$p['post_status'] = 'publish';
		$p['post_title'] = isset($_POST['stream_title']) ? $_POST['stream_title'] : '';
		$p['post_content'] = isset($_POST['stream_content']) ? $_POST['stream_content'] : '';
		$p['post_author'] = get_current_user_id();
		
		wp_insert_post($p, $wp_error);
		
		wp_redirect($_SERVER['REQUEST_URI']);
		exit;
	}
}

function stream_the_title($title) {
	if (is_admin() && $title == '') {
		$title = get_the_time( stream_datetime_format() );
	}
	
	return $title;
}

function stream_pre_get_posts($query) {
	if ($query->is_main_query() && is_home()) {
		$query->set('posts_per_page', -1);
	}
}

function stream_js_action() {
	if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['stream_js_action'] && !is_admin()) {
		$output = new stdClass();
		
		switch ($_POST['stream_js_action']) {
			case 'loadPosts':
				if (isset($_POST['time'])) {
					query_posts( array( 'date_query' => array( 'after' => WP_Date_Query::build_mysql_datetime($_POST['time']) ), 'posts_per_page' => -1 ) );
					
					ob_start();
					get_template_part('listing');
					
					$output->type = 'listing';
					$output->time = current_time('mysql');
					$output->markup = ob_get_clean();
				} else {
					$output->type = 'error';
					$output->message = 'The "time" parameter is required for "loadPosts" action';
				}
				break;
			default:
				$output->type = 'error';
				$output->message = 'No action specified';
				break;
		}
		
		echo json_encode($output);
		exit;
	}
}
?>