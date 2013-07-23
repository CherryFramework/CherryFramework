<?php
/*	Register and load javascript and stylesheet
/*-----------------------------------------------------------------------------------*/
function my_script() {
	if (!is_admin()) {
//---------------------------------------------
//include javascript
//---------------------------------------------
		wp_deregister_script('jquery');
		wp_register_script('jquery', get_template_directory_uri().'/js/jquery-1.7.2.min.js', false, '1.7.2');
		wp_enqueue_script('jquery');
	
		wp_enqueue_script('modernizr', get_template_directory_uri().'/js/modernizr.js', array('jquery'), '2.0.6');
		wp_enqueue_script('superfish', get_template_directory_uri().'/js/superfish.js', array('jquery'), '1.5.3');
		wp_enqueue_script('easing', get_template_directory_uri().'/js/jquery.easing.1.3.js', array('jquery'), '1.3');
		wp_enqueue_script('prettyPhoto', get_template_directory_uri().'/js/jquery.prettyPhoto.js', array('jquery'), '3.1.5');
		wp_enqueue_script('elastislide', get_template_directory_uri().'/js/jquery.elastislide.js', array('jquery'), '1.0');
		wp_enqueue_script('swfobject', home_url().'/wp-includes/js/swfobject.js', array('jquery'), '2.2');
		wp_enqueue_script('mobilemenu', get_template_directory_uri().'/js/jquery.mobilemenu.js', array('jquery'), '1.0');
		wp_enqueue_script('flexslider', get_template_directory_uri().'/js/jquery.flexslider.js', array('jquery'), '2.1');
		wp_enqueue_script('jflickrfeed', get_template_directory_uri().'/js/jflickrfeed.js', array('jquery'), '1.0');
		wp_enqueue_script('camera', get_template_directory_uri().'/js/camera.min.js', array('jquery'), '1.3.4');
		wp_enqueue_script('zaccordion', get_template_directory_uri().'/js/jquery.zaccordion.min.js', array('jquery'), '2.1.0');
		wp_enqueue_script('playlist', get_template_directory_uri().'/js/jplayer.playlist.min.js', array('jquery'), '2.1.0');
		wp_enqueue_script('jplayer', get_template_directory_uri().'/js/jquery.jplayer.min.js', array('jquery'), '2.2.0');
		wp_enqueue_script('debouncedresize', get_template_directory_uri().'/js/jquery.debouncedresize.js', array('jquery'), '1.0');
		wp_enqueue_script('isotope', get_template_directory_uri().'/js/jquery.isotope.js', array('jquery'), '1.5.25');
		wp_enqueue_script('custom', get_template_directory_uri().'/js/custom.js', array('jquery'), '1.0');
		
		// Bootstrap Scripts
		wp_enqueue_script('bootstrap', get_template_directory_uri().'/bootstrap/js/bootstrap.min.js', array('jquery'), '2.3.0');

//---------------------------------------------
//include stylesheet
//---------------------------------------------
		wp_enqueue_style('stylesheet', '//netdna.bootstrapcdn.com/font-awesome/3.2.0/css/font-awesome.css', false, '3.1.1', 'all');
	}
}
add_action('init', 'my_script');

/*	Register and load admin javascript
/*-----------------------------------------------------------------------------------*/

function tz_admin_js($hook) {
	$pages_array = array('post.php', 'post-new.php', 'themes.php');
	if (in_array($hook, $pages_array)) {
		wp_register_script('tz-admin', get_template_directory_uri() . '/js/jquery.custom.admin.js', 'jquery');
		wp_enqueue_script('tz-admin');
	}
}
add_action('admin_enqueue_scripts','tz_admin_js',10,1);
?>