<?php
/**
 * Mini Post Grid
 *
 */
if (!function_exists('mini_posts_grid_shortcode')) {

	function mini_posts_grid_shortcode($atts, $content = null) {
		extract(shortcode_atts(array(
			'type' => '',
			'numb' => '8',
			'thumbs' => '',
			'thumb_width' => '',
			'thumb_height' => '',
			'order_by' => 'date',
			'order' => 'DESC',
			'align' => '',
			'custom_class' => ''
		), $atts));

		$template_url = get_stylesheet_directory_uri();

		// check what order by method user selected
		switch ($order_by) {
			case 'date':
				$order_by = 'post_date';
				break;
			case 'title':
				$order_by = 'title';
				break;
			case 'popular':
				$order_by = 'comment_count';
				break;
			case 'random':
				$order_by = 'rand';
				break;
		}

		// check what order method user selected (DESC or ASC)
		switch ($order) {
			case 'DESC':
				$order = 'DESC';
				break;
			case 'ASC':
				$order = 'ASC';
				break;
		}

		// thumbnail size
		$thumb_x = 0;
		$thumb_y = 0;
		if (($thumb_width != '') && ($thumb_height != '')) {
			$thumbs = 'custom_thumb';
			$thumb_x = $thumb_width;
			$thumb_y = $thumb_height;
		} else {
			switch ($thumbs) {
				case 'small':
					$thumb_x = 110;
					$thumb_y = 110;
					break;
				case 'smaller':
					$thumb_x = 90;
					$thumb_y = 90;
					break;
				case 'smallest':
					$thumb_x = 60;
					$thumb_y = 60;
					break;
			}
		}	

			global $post;
			global $my_string_limit_words;
							
			$args = array(
				'post_type' => $type,
				'numberposts' => $numb,
				'orderby' => $order_by,
				'order' => $order
			);		

			$posts = get_posts($args);
			$i = 0;

			$output = '<ul class="mini-posts-grid grid-align-'.$align.' unstyled '.$custom_class.'">';
			
			foreach($posts as $post) {
				setup_postdata($post);
				$excerpt = get_the_excerpt();
				$attachment_url = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'full' );
				$url = $attachment_url['0'];
				$image = aq_resize($url, $thumb_x, $thumb_y, true);
				$mediaType = get_post_meta($post->ID, 'tz_portfolio_type', true);
				$prettyType = 0;

					$output .= '<li class="'.$thumbs.'">';
						if(has_post_thumbnail($post->ID) && $mediaType == 'Image') {
												
							$prettyType = 'prettyPhoto';									

							$output .= '<figure class="featured-thumbnail thumbnail">';
							$output .= '<a href="'.$url.'" title="'.get_the_title($post->ID).'" rel="' .$prettyType.'">';
							$output .= '<img  src="'.$image.'" alt="'.get_the_title($post->ID).'" />';
							$output .= '<span class="zoom-icon"></span></a></figure>';
						} elseif ($mediaType != 'Video' && $mediaType != 'Audio') {							

							$thumbid = 0;
							$thumbid = get_post_thumbnail_id($post->ID);
											
							$images = get_children( array(
								'orderby' => 'menu_order',
								'order' => 'ASC',
								'post_type' => 'attachment',
								'post_parent' => $post->ID,
								'post_mime_type' => 'image',
								'post_status' => null,
								'numberposts' => -1
							) ); 

							if ( $images ) {

								$k = 0;
								//looping through the images
								foreach ( $images as $attachment_id => $attachment ) {
									$prettyType = "prettyPhoto[gallery".$i."]";								
									//if( $attachment->ID == $thumbid ) continue;

									$image_attributes = wp_get_attachment_image_src( $attachment_id, 'full' ); // returns an array
									$img = aq_resize( $image_attributes[0], $thumb_x, $thumb_y, true ); //resize & crop img
									$alt = get_post_meta($attachment->ID, '_wp_attachment_image_alt', true);
									$image_title = $attachment->post_title;
									
									if ( $k == 0 ) {
										if (has_post_thumbnail($post->ID)) {
											$output .= '<figure class="featured-thumbnail thumbnail">';
											$output .= '<a href="'.$image_attributes[0].'" title="'.get_the_title($post->ID).'" rel="' .$prettyType.'">';
											$output .= '<img src="'.$image.'" alt="'.get_the_title($post->ID).'" />';
										} else {
											$output .= '<figure class="featured-thumbnail thumbnail">';
											$output .= '<a href="'.$image_attributes[0].'" title="'.get_the_title($post->ID).'" rel="' .$prettyType.'">';
											$output .= '<img  src="'.$img.'" alt="'.get_the_title($post->ID).'" />';
										}	
									} else {
										$output .= '<figure class="featured-thumbnail thumbnail" style="display:none;">';
										$output .= '<a href="'.$image_attributes[0].'" title="'.get_the_title($post->ID).'" rel="' .$prettyType.'">';
										$output .= '<img  src="'.$img.'" alt="'.get_the_title($post->ID).'" />';
									}
									$output .= '<span class="zoom-icon"></span></a></figure>';
									$k++;
								}					
							} elseif (has_post_thumbnail($post->ID)) {
								$prettyType = 'prettyPhoto';
								$output .= '<figure class="featured-thumbnail thumbnail">';
								$output .= '<a href="'.$url.'" title="'.get_the_title($post->ID).'" rel="' .$prettyType.'">';
								$output .= '<img  src="'.$image.'" alt="'.get_the_title($post->ID).'" />';
								$output .= '<span class="zoom-icon"></span></a></figure>';
							} else {
								// empty_featured_thumb.gif - for post without featured thumbnail
								$output .= '<figure class="featured-thumbnail thumbnail">';
								$output .= '<a href="'.get_permalink($post->ID).'" title="'.get_the_title($post->ID).'">';
								$output .= '<img  src="'.$template_url.'/images/empty_thumb.gif" alt="'.get_the_title($post->ID).'" />';
								$output .= '</a></figure>';
							}
						} else {

							// for Video and Audio post format - no lightbox
							$output .= '<figure class="featured-thumbnail thumbnail"><a href="'.get_permalink($post->ID).'" title="'.get_the_title($post->ID).'">';
							$output .= '<img  src="'.$image.'" alt="'.get_the_title($post->ID).'" />';
							$output .= '</a></figure>';
						}

						$output .= '</li>';
				$i++;		

			} // end foreach
			$output .= '</ul><!-- .posts-grid (end) -->';		
		$output .= '<div class="clear"></div>';
		return $output;
	} 
	add_shortcode('mini_posts_grid', 'mini_posts_grid_shortcode');
	
}?>