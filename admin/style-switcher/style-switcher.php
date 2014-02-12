<?php
/**
 * Style Switcher.
 *
 * @package Cherry_Style_Switcher
 * @author  CherryTeam
 */

// Include the Cherry_Style_Switcher class.
require_once( dirname( __FILE__ ) . '/public/class-cherry-style-switcher.php' );

add_action('init', array( 'Cherry_Style_Switcher', 'get_instance' ));

// Delete demo stylesheet
// add_action( 'init', 'cherry_delete_demo_css' );
function cherry_delete_demo_css() {
	if ( FILE_WRITEABLE ) {
		$demo_file = '/demo-style.css';

		if ( CURRENT_THEME == 'cherry' ) {
			$demo_file = '/css' . $demo_file;
		}

		if ( file_exists( CHILD_DIR . $demo_file ) ) {
			$body_class = get_body_class();
			if ( empty($body_class) || in_array('no-customize-support', $body_class) ) {
				unlink( CHILD_DIR . $demo_file );
			}
		}
	}
}