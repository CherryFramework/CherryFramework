<?php
/**
 * Banner
 *
 */
if (!function_exists('banner_shortcode')) {

	function banner_shortcode($atts, $content = null) {
		extract(shortcode_atts(
			array(
				'img'          => '',
				'banner_link'  => '',
				'title'        => '',
				'text'         => '',
				'btn_text'     => '',
				'target'       => '',
				'custom_class' => ''
		), $atts));

		// get attribute
		$content_url = content_url();
		$content_str = 'wp-content';

		$pos = strpos($img, $content_str);
		if ($pos !== false) {
			$img_new = substr( $img, $pos+strlen($content_str), strlen($img)-$pos );
			$img     = $content_url.$img_new;
		}

		$output =  '<div class="banner-wrap '.$custom_class.'">'; 
		if ($img !="") {
			$output .= '<figure class="featured-thumbnail">';
			if ($banner_link != "") {
				$output .= '<a href="'. $banner_link .'" title="'. $title .'"><img src="' . $img .'" title="'. $title .'" alt="" /></a>';
			} else {
				$output .= '<img src="' . $img .'" title="'. $title .'" alt="" />';
			}
			$output .= '</figure>';
		}
		if ($title!="") {
			$output .= '<h5>';
			$output .= $title;
			$output .= '</h5>';
		}
		if ($text!="") {
			$output .= '<p>';
			$output .= $text;
			$output .= '</p>';
		}
		if ($btn_text!="") {
			$output .=  '<div class="link-align banner-btn"><a href="'.$banner_link.'" title="'.$btn_text.'" class="btn btn-link" target="'.$target.'">';
			$output .= $btn_text;
			$output .= '</a></div>';
		}
		$output .= '</div><!-- .banner-wrap (end) -->';
		return $output;

	}
	add_shortcode('banner', 'banner_shortcode');
}?>