<?php
/**
 * Toggle
 *
 */
global $my_accordion_shortcode_count;
$my_accordion_shortcode_count = 0;

global $my_global_var;
$my_global_var = rand();

function my_display_shortcode_accordion($atts,$content) {
	global $my_global_var, $post, $my_accordion_shortcode_count;
	extract(shortcode_atts(array(
		'title' => null,
		'class' => null,
		'visible' => null
	), $atts));
	
	$toggleid = rand();
	
	if($visible!='') {
		$inClass = "in";
		$activeClass = "active";
	} else {
		$inClass = "";
		$activeClass = "";
	}

	$output = '<div class="accordion-group">';
		$output .= '<div class="accordion-heading">';
			$output .= '<a class="accordion-toggle '.$activeClass.'" data-toggle="collapse" data-parent="#id-'.$my_global_var.'" href="#'.$toggleid.'">'.$title.'</a>';
		$output .= '</div>';
		$output .= '<div class="accordion-body collapse '.$inClass.'" id="'.$toggleid.'">';
			$output .= '<div class="accordion-inner">';
				$output .= do_shortcode( $content );
			$output .= '</div>';
		$output .= '</div>';
	$output .= '</div>';

	$my_accordion_shortcode_count++;
	return $output;
}

function my_display_shortcode_accordions($attr,$content)
{
	// wordpress function 
	global $my_accordion_shortcode_count,$post,$my_global_var;

	$output = '<div id="id-'.$my_global_var.'" class="accordion">';
		$output .= do_shortcode( $content );
	$output .= '</div>';

	$my_global_var++;
	return str_replace("\r\n", '',$output);
}

function my_accordions_shortcode_init() {
    
	add_shortcode('accordion', 'my_display_shortcode_accordion'); // Single accordion
	add_shortcode('accordions', 'my_display_shortcode_accordions'); // Accordion Wrapper 
}

add_action('init','my_accordions_shortcode_init');
?>