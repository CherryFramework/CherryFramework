<?php
/**/
// TEMP: Enable update check on every request. Normally you don't need this! This is for testing only!
// set_site_transient('update_themes', null);

// NOTE: All variables and functions will need to be prefixed properly to allow multiple plugins to be updated

/******************Change this*******************/
@define('API_URL', 'http://updates.cherry.template-help.com/cherrymoto/v3/api/');
/************************************************/

/*******************Child Theme******************
//Use this section to provide updates for a child theme
//If using on child theme be sure to prefix all functions properly to avoid
//function exists errors
if(function_exists('wp_get_theme')){
	$theme_data = wp_get_theme(get_option('stylesheet'));
	$theme_version = $theme_data->Version;
} else {
	$theme_data = get_theme_data( get_stylesheet_directory() . '/style.css');
	$theme_version = $theme_data['Version'];
}
$theme_base = get_option('stylesheet');
**************************************************/


/***********************Parent Theme**************/
if(function_exists('wp_get_theme')){
	$theme_data = wp_get_theme(get_option('template'));
	$theme_version = $theme_data->Version;
} else {
	$theme_data = get_theme_data( PARENT_DIR . '/style.css');
	$theme_version = $theme_data['Version'];
}
$theme_base = get_option('template');
/**************************************************/

//Uncomment below to find the theme slug that will need to be setup on the api server

add_filter('pre_set_site_transient_update_themes', 'check_for_update');

function check_for_update($checked_data) {
	global $wp_version, $theme_version, $theme_base;

	$request = array(
		'slug' => $theme_base,
		'version' => $theme_version
	);

	// Start checking for an update
	$send_for_check = array(
		'body' => array(
			'action' => 'theme_update',
			'request' => serialize($request),
			'api-key' => md5(get_bloginfo('url'))
		),
		'user-agent' => 'WordPress/' . $wp_version . '; ' . get_bloginfo('url')
	);
	$raw_response = wp_remote_post(API_URL, $send_for_check);
	if (!is_wp_error($raw_response) && ($raw_response['response']['code'] == 200))
		$response = unserialize($raw_response['body']);

	// Feed the update data into WP updater
	if (!empty($response)){
		$checked_data->response[$theme_base] = $response;
		update_option('cherry_new_version', $response["new_version"]);
		update_option('cherry_url_info', $response["url"]);
	}
	return $checked_data;
}
// Take over the Theme info screen on WP multisite
add_filter('themes_api', 'my_theme_api_call', 10, 3);

function my_theme_api_call($def, $action, $args) {
	global $theme_base, $theme_version;

	if ( !isset($args->slug) )
		return false;

	if ($args->slug != $theme_base)
		return false;

	// Get the current version
	$args->version = $theme_version;
	$request_string = prepare_request($action, $args);
	$request = wp_remote_post(API_URL, $request_string);

	if (is_wp_error($request)) {
		$res = new WP_Error('themes_api_failed', theme_locals("themes_api_failed"), $request->get_error_message());
	} else {
		$res = unserialize($request['body']);

		if ($res === false)
			$res = new WP_Error('themes_api_failed', theme_locals("themes_api_failed_2"), $request['body']);
	}

	return $res;
}
if (is_admin()){
	$current = get_transient('update_themes');
}

add_action( 'admin_notices', 'wp_persistant_notice' );
function wp_persistant_notice() {
	global $pagenow;
	$cherry_url_info = get_option('cherry_url_info');
	$cherry_new_version = get_option('cherry_new_version');
	$cherry_version = get_theme_info(PARENT_NAME, 'Version');
	$pageHidden = array("update.php", "update-core.php", 'cherry-options_page_options-framework-data-management', 'admin.php');
	$update_url = wp_nonce_url('update.php?action=upgrade-theme&amp;theme=' . urlencode(PARENT_NAME), 'upgrade-theme_'.urlencode(PARENT_NAME));

	if (! get_user_meta(get_current_user_id(), '_wp_hide_notice', true) &&  !in_array($pagenow, $pageHidden) && is_admin() && ($cherry_new_version > $cherry_version ) ) {
		printf( '<div class="updated"><p><strong>%1$s <a href="%2$s" class="thickbox" title="cherry">%3$s</a> %4$s <a href="%5$s" onclick="%6$s">%7$s</a><br>%8$s<br><a href="%9$s"> %10$s </a></strong></p></div>', theme_locals('new_version'), $cherry_url_info.'?TB_iframe=true&width=1024&height=800', theme_locals('view_version').' '.$cherry_new_version.' '.theme_locals('details'), theme_locals('or'), $update_url, "if ( confirm('Updating this theme will lose any customizations you have made. \'Cancel\' to stop, \'OK\' to update.') ) {return true;}return false;", theme_locals('update_now'), theme_locals('info_box_4'), esc_url(add_query_arg( 'wp_nag', wp_create_nonce( 'wp_nag' ))), theme_locals('dismiss_notice'));
	}
}
add_action( 'admin_init', 'wp_hide_notice' );
function wp_hide_notice() {
	if ( ! isset( $_GET['wp_nag'] ) ) {
		return;
	}
	check_admin_referer( 'wp_nag', 'wp_nag' );
	update_user_meta( get_current_user_id(), '_wp_hide_notice', 1 );
}
?>