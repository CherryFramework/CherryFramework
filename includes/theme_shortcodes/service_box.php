<?php
/**
 * Service Box
 *
 */
if (!function_exists('service_box_shortcode')) {

	function service_box_shortcode($atts, $content = null) { 
	    extract(shortcode_atts(
	        array(
				'title' => '',
				'subtitle' => '',
				'icon' => '',
				'text' => '',
				'btn_text' => theme_locals('read_more'),
				'btn_link' => '',
				'btn_size' => '',
				'target' => '',
				'custom_class' => ''
	    ), $atts));
		
		$template_url = get_stylesheet_directory_uri();
	 
		$output =  '<div class="service-box '.$custom_class.'">';
		
		// check what icon user selected
		switch ($icon) {
			case 'no':
				break;
	       	case 'icon1':
				$output .= '<figure class="icon"><img src="' .$template_url. '/images/'. $icon .'.png" alt="" /></figure>';
				break;
	       	case 'icon2':
				$output .= '<figure class="icon"><img src="' .$template_url. '/images/'. $icon .'.png" alt="" /></figure>';
				break;
			case 'icon3':
				$output .= '<figure class="icon"><img src="' .$template_url. '/images/'. $icon .'.png" alt="" /></figure>';
				break;
			case 'icon4':
				$output .= '<figure class="icon"><img src="' .$template_url. '/images/'. $icon .'.png" alt="" /></figure>';
				break;
			case 'icon5':
				$output .= '<figure class="icon"><img src="' .$template_url. '/images/'. $icon .'.png" alt="" /></figure>';
				break;
			case 'icon6':
				$output .= '<figure class="icon"><img src="' .$template_url. '/images/'. $icon .'.png" alt="" /></figure>';
				break;
			case 'icon7':
				$output .= '<figure class="icon"><img src="' .$template_url. '/images/'. $icon .'.png" alt="" /></figure>';
				break;
			case 'icon8':
				$output .= '<figure class="icon"><img src="' .$template_url. '/images/'. $icon .'.png" alt="" /></figure>';
				break;
			case 'icon9':
				$output .= '<figure class="icon"><img src="' .$template_url. '/images/'. $icon .'.png" alt="" /></figure>';
				break;
			case 'icon10':
				$output .= '<figure class="icon"><img src="' .$template_url. '/images/'. $icon .'.png" alt="" /></figure>';
				break;
	    }

	   $output .= '<div class="service-box_body">';
	 
		if ($title!="") {
			$output .= '<h2 class="title">';
			$output .= $title;
			$output .= '</h2>';
		}	 
		if ($subtitle!="") {
			$output .= '<h5 class="sub-title">';
			$output .= $subtitle;
			$output .= '</h5>';
		}		
		if ($text!="") {
			$output .= '<div class="service-box_txt">';
			$output .= $text;
			$output .= '</div>';
		}		
		if ($btn_link!="") {	
			$output .=  '<div class="btn-align"><a href="'.$btn_link.'" title="'.$btn_text.'" class="btn btn-inverse btn-'.$btn_size.' btn-primary " target="'.$target.'">';
			$output .= $btn_text;
			$output .= '</a></div>';
		}
		$output .= '</div>';	 
		$output .= '</div><!-- /Service Box -->';	 
	    return $output;	 
	} 
	add_shortcode('service_box', 'service_box_shortcode');

}?>