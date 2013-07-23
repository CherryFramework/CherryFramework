<?php
/**
 * Roundabout
 *
 */
if (!function_exists('shortcode_roundabout')) {
	
	function shortcode_roundabout($atts, $content = null) {
			extract(shortcode_atts(array(
				'title'            => '',
				'num'              => '3',
				'type'             => '',
				'thumb_width'      => '375',
				'thumb_height'     => '250',
				'category'         => '',
				'custom_category'  => '',
				'more_button_text' => '',
				'more_button_link' => '',
				'custom_class'     => ''
			), $atts));

			wp_enqueue_script( 'roundabout_script', get_template_directory_uri() . '/js/jquery.roundabout.min.js', array( 'jquery' ) );
			wp_enqueue_script( 'roundabout_shape', get_template_directory_uri() . '/js/jquery.roundabout-shapes.min.js', array( 'jquery' ) );

			$template_url = get_stylesheet_directory_uri();
			
			// check what type of post user selected
			switch ($type) {
			   	case 'blog':
					$type_post = 'post';
					break;
			   	case 'portfolio':
					$type_post = 'portfolio';
					break;
				default:
					$type_post = 'post';
					break;
			}		

			$output = '<div class="roundabout-holder '.$custom_class.'">';
			if ($title != '') {
				$output .= '<h2>'.$title.'</h2>';
			}
			$output .= '<ul id="roundabout-list">';
			
			global $post;

			// WPML filter
			$suppress_filters = get_option('suppress_filters');

			$args = array(
				'post_type'              => $type_post,
				'category_name'          => $category,
				$type_post . '_category' => $custom_category,
				'numberposts'            => -1,
				'orderby'                => 'post_date',
				'order'                  => 'DESC',
				'suppress_filters'       => $suppress_filters
			);

			$posts = get_posts($args);
			$i = 1;
			
			foreach($posts as $key => $post) {
				// Unset not translated posts
				if ( function_exists( 'wpml_get_language_information' ) ) {
					global $sitepress;

					$check              = wpml_get_language_information( $post->ID );
					$language_code      = substr( $check['locale'], 0, 2 );
					if ( $language_code != $sitepress->get_current_language() ) unset( $posts[$key] );

					// Post ID is different in a second language Solution
					if ( function_exists( 'icl_object_id' ) ) $post = get_post( icl_object_id( $post->ID, $type_post, true ) );
				}
				setup_postdata($post);
				$attachment_url = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'full' );
				$url            = $attachment_url['0'];
				$image          = aq_resize($url, $thumb_width, $thumb_height, true);

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
}?>