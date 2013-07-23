<?php
/**
 * Roundabout
 *
 */
if (!function_exists('shortcode_roundabout')) {
	
	function shortcode_roundabout($atts, $content = null) {
			extract(shortcode_atts(array(
				'title' => '',
				'num' => '3',
				'type' => '',
				'thumb_width' => '375',
				'thumb_height' => '250',			
				'category' => '',
				'custom_category' => '',
				'more_button_text' => '',
				'more_button_link' => '',
				'custom_class' => ''
			), $atts));

			$template_url = get_stylesheet_directory_uri();
			
			// check what type of post user selected
			switch ($type) {
			   	case 'blog':
					$type_post = '';
					break;
			   	case 'portfolio':
					$type_post = 'portfolio';
					break;
				default:
					$type_post = '';
					break;
			}		

			$output = '<div class="roundabout-holder '.$custom_class.'">';
			if ($title != '') {
				$output .= '<h2>'.$title.'</h2>';
			}
			$output .= '<ul id="roundabout-list">';
			
			global $post;		
			$args = array(
				'post_type' => $type_post,
				'category_name' => $category,
				$type_post . '_category' => $custom_category,
				'numberposts' => -1,
				'orderby' => 'post_date',
				'order' => 'DESC'
			);

			$posts = get_posts($args);
			$i = 1;
			
			foreach($posts as $post) {
				setup_postdata($post);
				$attachment_url = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'full' );
				$url = $attachment_url['0'];
				$image = aq_resize($url, $thumb_width, $thumb_height, true);

				if ($i <= $num) {
					if (has_post_thumbnail($post->ID)) {
						$output .= '<li>';
						$output .= '<a href="'.get_permalink($post->ID).'" title="'.get_the_title($post->ID).'">';
						$output .= '<img  src="'.$image.'" alt="'.get_the_title($post->ID).'" />';
						$output .= '</a>';
						$output .= '</li>';
						$i++;
					}
				}	else
						break;
			}
			$output .= '</ul>';

			if (($more_button_text != '') && ($more_button_link != '')) {
				$output .= '<a class="btn btn-primary" href="' . $more_button_link . '">' . $more_button_text . '</a>';
			}		
		   
			$output .= '<script>
					jQuery(document).ready(function() {
						jQuery("#roundabout-list").roundabout({
							minOpacity: 1,
							minScale: 0.6,
							minZ: 0,
							shape: "square",
					        responsive: true
					    });
					});
					jQuery(window).bind("resize", function() {
					    jQuery("#roundabout-list li").removeAttr("style");
					    jQuery("#roundabout-list").roundabout({
					        minOpacity: 1,
							minScale: 0.6,
							responsive: false
					    });
					});';
			$output .= '</script>';		
	       	$output .= '</div>';
			return $output;
	}
	add_shortcode('roundabout', 'shortcode_roundabout');
}

add_action( 'wp_enqueue_scripts', 'roundabout_scripts' );
/**
 * Enqueue the javascript on the front end
 */
function roundabout_scripts() {
	wp_enqueue_script( 'roundabout_script', get_template_directory_uri() . '/js/jquery.roundabout.min.js', array( 'jquery' ) );
	wp_enqueue_script( 'roundabout_shape', get_template_directory_uri() . '/js/jquery.roundabout-shapes.min.js', array( 'jquery' ) );
}?>