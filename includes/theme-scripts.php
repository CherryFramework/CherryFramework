<?php
/*-----------------------------------------------------------------------------------*/
/*	Register and load javascript
/*-----------------------------------------------------------------------------------*/
function cherry_scripts() {
	if (!is_admin()) {
		// CherryFramework Scripts
		wp_deregister_script('jquery');
		wp_register_script('jquery', PARENT_URL.'/js/jquery-1.7.2.min.js', false, '1.7.2');
		wp_enqueue_script('jquery');
		wp_register_script('migrate', PARENT_URL.'/js/jquery-migrate-1.2.1.min.js', array('jquery'), '1.2.1');
		wp_enqueue_script('migrate');

		wp_register_script('modernizr', PARENT_URL.'/js/modernizr.js', array('jquery'), '2.0.6');
		wp_register_script('jflickrfeed', PARENT_URL.'/js/jflickrfeed.js', array('jquery'), '1.0');
		wp_register_script('superfish', PARENT_URL.'/js/superfish.js', array('jquery'), '1.5.3', true);
		wp_register_script('mobilemenu', PARENT_URL.'/js/jquery.mobilemenu.js', array('jquery'), '1.0', true);
		wp_register_script('easing', PARENT_URL.'/js/jquery.easing.1.3.js', array('jquery'), '1.3', true);
		wp_register_script('magnific-popup', PARENT_URL.'/js/jquery.magnific-popup.min.js', array('jquery'), '0.9.3', true);
		wp_register_script('flexslider', PARENT_URL.'/js/jquery.flexslider.js', array('jquery'), '2.1', true);
		wp_register_script('playlist', PARENT_URL.'/js/jplayer.playlist.min.js', array('jquery'), '2.3.0', true);
		wp_register_script('jplayer', PARENT_URL.'/js/jquery.jplayer.min.js', array('jquery'), '2.6.0', true);
		wp_register_script('tmstickup', PARENT_URL.'/js/tmstickup.js', array('jquery'), '1.0.0', true);
		wp_register_script('device', PARENT_URL.'/js/device.min.js', array('jquery'), '1.0.0', true);
		wp_register_script('custom', PARENT_URL.'/js/custom.js', array('jquery'), '1.0');

		wp_enqueue_script('swfobject');
		wp_enqueue_script('modernizr');
		wp_enqueue_script('jflickrfeed');
		wp_enqueue_script('superfish');
		wp_enqueue_script('mobilemenu');
		wp_enqueue_script('easing');
		wp_enqueue_script('magnific-popup');
		wp_enqueue_script('flexslider');
		wp_enqueue_script('playlist');
		wp_enqueue_script('jplayer');
		wp_enqueue_script('tmstickup');
		wp_enqueue_script('device');
		wp_enqueue_script('custom');

		wp_register_script('zaccordion', PARENT_URL.'/js/jquery.zaccordion.min.js', array('jquery'), '2.1.0', true);
		wp_enqueue_script('zaccordion');
		wp_register_script('camera', PARENT_URL.'/js/camera.min.js', array('jquery'), '1.3.4', true);
		wp_enqueue_script('camera');

		// only Portfolio (2-*, 3-*, 4-Columns), Home and Front Pages
		if ( (is_page_template('page-Portfolio2Cols-filterable.php'))
			|| (is_page_template('page-Portfolio3Cols-filterable.php'))
			|| (is_page_template('page-Portfolio4Cols-filterable.php'))
			|| is_home()
			|| is_front_page()) {
			wp_register_script('debouncedresize', PARENT_URL.'/js/jquery.debouncedresize.js', array('jquery'), '1.0', true);
			wp_register_script('ba-resize', PARENT_URL.'/js/jquery.ba-resize.min.js', array('jquery'), '1.1', true);
			wp_register_script('isotope', PARENT_URL.'/js/jquery.isotope.js', array('jquery'), '1.5.25', true);
			wp_enqueue_script('debouncedresize');
			wp_enqueue_script('ba-resize');
			wp_enqueue_script('isotope');
		}
		// only child theme's where overwrite flickr widget
		if ( (CURRENT_THEME!='cherry') && (file_exists(CHILD_DIR. '/includes/widgets/my-flickr-widget.php')) ) {
			wp_register_script('prettyPhoto', PARENT_URL.'/js/jquery.prettyPhoto.js', array('jquery'), '3.1.5');
			wp_enqueue_script('prettyPhoto');
		}
		// Bootstrap Scripts
		wp_register_script('bootstrap', PARENT_URL.'/bootstrap/js/bootstrap.min.js', array('jquery'), '2.3.0');
		wp_enqueue_script('bootstrap');
	}
}
add_action('wp_enqueue_scripts', 'cherry_scripts');

/*-----------------------------------------------------------------------------------*/
/*	Register and load stylesheet
/*-----------------------------------------------------------------------------------*/
function cherry_stylesheets() {
	if ( CURRENT_THEME != 'cherry' ) {
		if ( file_exists( CHILD_DIR . '/main-style.css' ) ) {
			wp_enqueue_style( CURRENT_THEME, CHILD_URL . '/main-style.css', false, null, 'all' );
		}

		if ( file_exists( CHILD_DIR . '/includes/widgets/my-flickr-widget.php' ) ) {
			wp_register_style( 'prettyPhoto', PARENT_URL.'/css/prettyPhoto.css', false, '3.1.5', 'all' );
			wp_enqueue_style( 'prettyPhoto' );
		}
	}
	wp_enqueue_style( 'font-awesome', '//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css', false, '3.2.1', 'all' );
	wp_register_style( 'magnific-popup', PARENT_URL.'/css/magnific-popup.css', false, '0.9.3', 'all' );
	wp_enqueue_style( 'magnific-popup' );
}
add_action('wp_enqueue_scripts', 'cherry_stylesheets');

/*-----------------------------------------------------------------------------------*/
/*	Register and load admin javascript
/*-----------------------------------------------------------------------------------*/
function tz_admin_js($hook) {
	$pages_array = array('post.php', 'post-new.php');
	if (in_array($hook, $pages_array)) {
		wp_register_script('tz-admin', PARENT_URL . '/js/jquery.custom.admin.js', 'jquery');
		wp_enqueue_script('tz-admin');
	}
}
add_action('admin_enqueue_scripts', 'tz_admin_js', 10, 1);
?>