<?php
/**
 * Carousel
 *
 */
if (!function_exists('shortcode_carousel')) {

	function shortcode_carousel($atts, $content = null) {
			extract(shortcode_atts(array(
				'title'            => '',
				'num'              => '8',
				'type'             => '',
				'thumb'            => 'true',
				'thumb_width'      => '220',
				'thumb_height'     => '180',
				'more_text_single' => theme_locals('read_more'),
				'category'         => '',
				'custom_category'  => '',
				'excerpt_count'    => '12',
				'date'             => '',
				'author'           => '',
				'min_items'        => '3',
				'spacer'           => '18',
				'custom_class'     => ''
			), $atts));

			$template_url = get_stylesheet_directory_uri();
			
			// check what type of post user selected
			switch ($type) {
				case 'blog':
					$type_post = 'post';
					break;
				case 'portfolio':
					$type_post = 'portfolio';
					break;
				case 'testimonial':
					$type_post = 'testi';
					break;
			}

			$output = '<div class="carousel-wrap '.$custom_class.'">';
			if ($title != '') {
				$output .= '<h2>'.$title.'</h2>';
			}
			$output .= '<div id="carousel-'. $type .'" class="es-carousel-wrapper">';
			$output .= '<div class="es-carousel">';
			$output .= '<ul class="es-carousel_list unstyled">';
			
			global $post;
			global $my_string_limit_words;

			// WPML filter
			$suppress_filters = get_option('suppress_filters');
			
			$args = array(
				'post_type'              => $type_post,
				'category_name'          => $category,
				$type_post . '_category' => $custom_category,
				'numberposts'            => $num,
				'orderby'                => 'post_date',
				'order'                  => 'DESC',
				'suppress_filters'       => $suppress_filters
			);

			$latest = get_posts($args);
			$i = 0;
			
			foreach($latest as $key => $post) {
				// Unset not translated posts
				if ( function_exists( 'wpml_get_language_information' ) ) {
					global $sitepress;

					$check              = wpml_get_language_information( $post->ID );
					$language_code      = substr( $check['locale'], 0, 2 );
					if ( $language_code != $sitepress->get_current_language() ) unset( $latest[$key] );

					// Post ID is different in a second language Solution
					if ( function_exists( 'icl_object_id' ) ) $post = get_post( icl_object_id( $post->ID, $type_post, true ) );
				}
				setup_postdata($post);
				$excerpt         = get_the_excerpt();
				$format          = get_post_format();
				$attachment_url  = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'full' );
				$url             = $attachment_url['0'];
				$image           = aq_resize($url, $thumb_width, $thumb_height, true);
				$link_format_url = get_post_meta(get_the_ID(), 'tz_link_url', true);

				$output .= '<li class="es-carousel_li '.$format.'">';
					
					if ($thumb == 'true') {
						if (has_post_thumbnail($post->ID) && $format == 'image') {

							$output .= '<figure class="featured-thumbnail">';
							$output .= '<a href="'.get_permalink($post->ID).'" title="'.get_the_title($post->ID).'">';
							$output .= '<img  src="'.$image.'" alt="'.get_the_title($post->ID).'" />';
							$output .= '</a></figure>';

						} elseif ( $format != 'video' && $format != 'audio') {

							$thumbid = 0;
							$thumbid = get_post_thumbnail_id($post->ID);
							$images = get_children( array(
								'orderby'        => 'menu_order',
								'order'          => 'ASC',
								'post_type'      => 'attachment',
								'post_parent'    => $post->ID,
								'post_mime_type' => 'image',
								'post_status'    => null,
								'numberposts'    => -1
							) ); 

							if ( $images ) {

								$k = 0;
								//looping through the images
								foreach ( $images as $attachment_id => $attachment ) {
									//if( $attachment->ID == $thumbid ) continue;

									$image_attributes = wp_get_attachment_image_src( $attachment_id, 'full' ); // returns an array
									$img              = aq_resize($image_attributes[0], $thumb_width, $thumb_height, true);  //resize & crop img
									$alt              = get_post_meta($attachment->ID, '_wp_attachment_image_alt', true);
									$image_title      = $attachment->post_title;

									if ( $k == 0 ) {
										$output .= '<figure class="featured-thumbnail">';
										$output .= '<a href="'.get_permalink($post->ID).'" title="'.get_the_title($post->ID).'">';
										$output .= '<img src="'.$img.'" alt="'.get_the_title($post->ID).'" />';
									} else {
										$output .= '<figure class="featured-thumbnail" style="display:none;">';
										$output .= '<a href="'.get_permalink($post->ID).'" title="'.get_the_title($post->ID).'">';
										$output .= '<img src="'.$img.'" alt="'.get_the_title($post->ID).'" />';
									}
									$output .= '</a></figure>';
									$k++;
								}
							} elseif (has_post_thumbnail($post->ID)) {
								$output .= '<figure class="featured-thumbnail">';
								$output .= '<a href="'.get_permalink($post->ID).'" title="'.get_the_title($post->ID).'">';
								$output .= '<img src="'.$image.'" alt="'.get_the_title($post->ID).'" />';
								$output .= '</a></figure>';
							} /*else {
								// empty_featured_thumb.gif - for post without featured thumbnail
								$output .= '<figure class="featured-thumbnail">';
								$output .= '<a href="'.get_permalink($post->ID).'" title="'.get_the_title($post->ID).'">';
								$output .= '<img  src="'.$template_url.'/images/empty_thumb.gif" alt="'.get_the_title($post->ID).'" />';
								$output .= '</a></figure>';
							}*/
						} else {
							if (has_post_thumbnail($post->ID)) {
								// for Video and Audio post format - no lightbox
								$output .= '<figure class="featured-thumbnail"><a href="'.get_permalink($post->ID).'" title="'.get_the_title($post->ID).'">';
								$output .= '<img  src="'.$image.'" alt="'.get_the_title($post->ID).'" />';
								$output .= '</a></figure>';
							} /*else {
								// empty_featured_thumb.gif - for post without featured thumbnail
								$output .= '<figure class="featured-thumbnail">';
								$output .= '<a href="'.get_permalink($post->ID).'" title="'.get_the_title($post->ID).'">';
								$output .= '<img  src="'.$template_url.'/images/empty_thumb.gif" alt="'.get_the_title($post->ID).'" />';
								$output .= '</a></figure>';
							}*/
						}
					}

					$output .= '<div class="desc">';
					if ($date == "yes") {
						$output .= '<time datetime="'.get_the_time('Y-m-d\TH:i:s', $post->ID).'">' .get_the_time('M', $post->ID). ' <span>'.get_the_time('d', $post->ID).'</span></time>';
					}

					if ($author == "yes") {
						$output .= '<em class="author">, '.theme_locals("by").' <a href="'.get_author_posts_url(get_the_author_meta( 'ID' )).'">'.get_the_author_meta('display_name').'</a></em>';
					}
					
					//Link format
					if ($format == "link") {
						$output .= '<h5><a href="'.$link_format_url.'" title="'.get_the_title($post->ID).'">';
						$output .= get_the_title($post->ID);
						$output .= '</a></h5>';

					//Other formats
					} else {
						$output .= '<h5><a href="'.get_permalink($post->ID).'" title="'.get_the_title($post->ID).'">';
						$output .= get_the_title($post->ID);
						$output .= '</a></h5>';
					}
					
					if($excerpt_count >= 1){
						$output .= '<p class="excerpt">';
						$output .= my_string_limit_words($excerpt,$excerpt_count);
						$output .= '</p>';
					}
					
					if($more_text_single!=""){
						$output .= '<a href="'.get_permalink($post->ID).'" class="btn btn-primary" title="'.get_the_title($post->ID).'">';
						$output .= $more_text_single;
						$output .= '</a>';
					}
					$output .= '</div>';
					
				$output .= '</li>';

			}
			$output .= '</ul>';
			$output .= '</div></div>';

			$output .= '<script>
					jQuery("#carousel-'. $type .'").elastislide({
						imageW 		: '.$thumb_width.',
						minItems	: '.$min_items.',
						speed		: 600,
						easing		: "easeOutQuart",
						margin		: '.$spacer.',
						border		: 0,
						onClick		: function() {}
					});';
			$output .= '</script>';
			
			$output .= '</div>';
			return $output;
	}
	add_shortcode('carousel', 'shortcode_carousel');
	
}?>