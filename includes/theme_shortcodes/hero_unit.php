<?php
/**
 * Hero Unit
 *
 */
if (!function_exists('hero_unit_shortcode')) {

	function hero_unit_shortcode($atts, $content = null) { 
	    extract(shortcode_atts(
	        array(
				'title' => '',
				'text' => '',
				'btn_text' => theme_locals('read_more'),
				'btn_link' => '',
				'btn_style' => '',
				'btn_size' => '',
				'target' => '',
				'custom_class' => ''
	    ), $atts));
	 
		$output =  '<div class="hero-unit '.$custom_class.'">';
	 
		if ($title!="") {
			$output .= '<h1>';
			$output .= $title;
			$output .= '</h1>';
		}
		
		if ($text!="") {
			$output .= '<p>';
			$output .= $text;
			$output .= '</p>';
		}
		
		if ($btn_link!="") {	
			$output .=  '<div class="btn-align"><a href="'.$btn_link.'" title="'.$btn_text.'" class="btn btn-'.$btn_style.' btn-'.$btn_size.' btn-primary" target="'.$target.'">';
			$output .= $btn_text;
			$output .= '</a></div>';
		}
	 
		$output .= '</div><!-- .hero-unit (end) -->'; 
	    return $output; 
	} 
	add_shortcode('hero_unit', 'hero_unit_shortcode');
	
}?>