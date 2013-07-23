<?php
/**
 * Alert boxes
 */
 if (!function_exists('shortcode_alert_box')) {

 	function shortcode_alert_box($atts, $content = null) {
		extract(shortcode_atts(
	        array(
				'style' => '',
				'close' => '',
				'custom_class' => ''
	    ), $atts));

		$output =  '<div class="alert alert-'.$style.' fade in '.$custom_class.'">';
		if ($close == 'yes') {
			$output .= '<button type="button" class="close" data-dismiss="alert">&times;</button>';
		}
		$output .= $content;
		$output .=  '</div>';
		return $output;
	}
	add_shortcode('alert_box', 'shortcode_alert_box');
	
 }?>