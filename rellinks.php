<?php
/*
Plugin Name: Dynamic Sitelinks
Plugin URI: http://www.z43studio.com
Description: Converts all Site URL references (links) in posts & pages to <pre><code>[site_domain]</code></pre> for allowing your Site URL to change without issue.
Version: 1.0
Author: z43 Studio
Author URI: http://www.z43studio.com
License: GPL2
*/
function rellink_sitedomain() {
	return get_site_url() . '/';
}
add_shortcode('site_domain', 'rellink_sitedomain');

function rellink_replace($content) {
	$content = str_replace(rellink_sitedomain(), '[site_domain]', $content);
	return $content;
}
add_filter('content_save_pre', 'rellink_replace', 99);
function rellink_replace_reverse($content) {
	$content = str_replace('[site_domain]', rellink_sitedomain(), $content);
	return $content;
}
add_filter('content_edit_pre', 'rellink_replace_reverse', 99);

function rellink_activate() {
	global $wpdb;
	
	$wpdb->query($wpdb->prepare("UPDATE $wpdb->posts SET post_content = REPLACE(post_content, %s, '[site_domain]');", rellink_sitedomain()));
}
register_activation_hook( __FILE__, 'rellink_activate' );
function rellink_deactivate($value='') {
	global $wpdb;
	
	$wpdb->query($wpdb->prepare("UPDATE $wpdb->posts SET post_content = REPLACE(post_content, '[site_domain]', %s);", rellink_sitedomain()));
}
register_deactivation_hook( __FILE__, 'rellink_deactivate' );
?>