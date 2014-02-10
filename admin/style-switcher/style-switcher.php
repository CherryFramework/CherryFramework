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