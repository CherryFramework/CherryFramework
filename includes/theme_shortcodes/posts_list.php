<?php
/**
 * Post List
 *
 */
if (!function_exists('posts_list_shortcode')) {

	function posts_list_shortcode($atts, $content = null) {
		extract(shortcode_atts(array(
			'type'         => 'post',
			'thumbs'       => '',
			'thumb_width'  => '',
			'thumb_height' => '',
			'post_content' => '',
			'numb'         => '5',
			'order_by'     => '',
			'order'        => '',
			'link'         => '',
			'link_text'    => theme_locals('read_more'),
			'tags'         => '',
			'custom_class' => ''
		), $atts));

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

		global $post;
		global $my_string_limit_words;
		global $_wp_additional_image_sizes;

		// WPML filter
			$suppress_filters = get_option('suppress_filters');

		$args = array(
			'post_type'        => $type,
			'numberposts'      => $numb,
			'orderby'          => $order_by,
			'order'            => $order,
			'suppress_filters' => $suppress_filters
		);

		$posts = get_posts($args);
		$i = 0;

		// thumbnail size
		$thumb_x = 0;
		$thumb_y = 0;
		if ($thumbs == 'large') {
			$thumb_x = 620;
			$thumb_y = 300;
		} else {
			$thumb_x = $_wp_additional_image_sizes['post-thumbnail']['width'];
			$thumb_y = $_wp_additional_image_sizes['post-thumbnail']['height'];
		}

		// thumbnail class
		$thumbs_class = '';
		if ($thumbs == 'large')
			$thumbs_class = 'large';

		$output = '<div class="posts-list '.$custom_class.'">';
		
		foreach($posts as $key => $post) {
			// Unset not translated posts
			if ( function_exists( 'wpml_get_language_information' ) ) {
				global $sitepress;

				$check              = wpml_get_language_information( $post->ID );
				$language_code      = substr( $check['locale'], 0, 2 );
				if ( $language_code != $sitepress->get_current_language() ) unset( $posts[$key] );

				// Post ID is different in a second language Solution
				if ( function_exists( 'icl_object_id' ) ) $post = get_post( icl_object_id( $post->ID, $type, true ) );
			}
			setup_postdata($post);
			$excerpt        = get_the_excerpt();
			$attachment_url = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'full' );
			$url            = $attachment_url['0'];
			if (($thumb_width != '') && ($thumb_height != ''))
				$image = aq_resize($url, $thumb_width, $thumb_height, true);
			else
				$image = aq_resize($url, $thumb_x, $thumb_y, true);
			
			$mediaType = get_post_meta($post->ID, 'tz_portfolio_type', true);
			$format = get_post_format();

				$output .= '<div class="row-fluid">';
				$output .= '<article class="span12 post__holder">';

					//post header
					$output .= '<header class="post-header">';
						$output .= '<h2 class="post-title"><a href="'.get_permalink($post->ID).'" title="'.get_the_title($post->ID).'">';
							$output .= get_the_title($post->ID);
						$output .= '</a></h2>';

						// post meta
						$output .= '<div class="post_meta">';

							// post category
							$output .= '<span class="post_category">';
							if ($type!='' && $type!='post') {
								$terms = get_the_terms( $post->ID, $type.'_category');
								if ( $terms && ! is_wp_error( $terms ) ) {
									$out = array();
									$output .= theme_locals('posted_in').' ';
									foreach ( $terms as $term )
										$out[] = '<a href="' .get_term_link($term->slug, $type.'_category') .'">'.$term->name.'</a>';
										$output .= join( ', ', $out );
								}
							} else {
								$categories = get_the_category();
								if($categories){
									$out = array();
									$output .= theme_locals('posted_in').' ';
									foreach($categories as $category)
										$out[] = '<a href="'.get_category_link($category->term_id ).'" title="'.$category->name.'">'.$category->cat_name.'</a> ';
										$output .= join( ', ', $out );
								}
							}
							$output .= '</span>';

						// post date
						$output .= '<span class="post_date">';
						$output .= '<time datetime="'.get_the_time('Y-m-d\TH:i:s', $post->ID).'">' .get_the_date(). '</time>';
						$output .= '</span>';

						// post author
						$output .= '<span class="post_author">';
						$output .= theme_locals('by').' ';
						$output .= '<a href="'.get_author_posts_url(get_the_author_meta( 'ID' )).'">'.get_the_author_meta('display_name').'</a>';
						$output .= '</span>';

						// post comment count
						$num = 0;
						$post_id = $post->ID;
						$queried_post = get_post($post_id);
						$cc = $queried_post->comment_count;
						if( $cc == $num || $cc > 1 ) : $cc = $cc.' '.theme_locals('comments');
						else : $cc = $cc.' '.theme_locals('comment');
						endif;
						$permalink = get_permalink($post_id);
						$output .= '<span class="post_comment">';
						$output .= '<a href="'. $permalink . '" class="comments_link">' . $cc . '</a>';
						$output .= '</span>';

						$output .= '</div>';
						$output .= cherry_get_post_networks(array('post_id' => $post_id, 'display_title' => false, 'output_type' => 'return'));
					$output .= '</header>';

					//post thumbnail
					if ((has_post_thumbnail($post->ID)) && ($format == 'image' || $mediaType == 'Image')) {

						$output .= '<figure class="featured-thumbnail thumbnail '.$thumbs_class.'">';
						$output .= '<a href="'.get_permalink($post->ID).'" title="'.get_the_title($post->ID).'">';
						$output .= '<img src="'.$image.'" alt="'.get_the_title($post->ID).'" />';
						$output .= '</a></figure>';

					} elseif ($mediaType != 'Video' && $mediaType != 'Audio') {

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

								$image_attributes_t = wp_get_attachment_image_src( $attachment_id); // returns an array (thumbnail size)
								$image_attributes = wp_get_attachment_image_src( $attachment_id, 'full' ); // returns an array (full size)
								if (($thumb_width != '') && ($thumb_height != '')) {
									$img = aq_resize($image_attributes[0], $thumb_width, $thumb_height, true);  //resize & crop img
								} else {
									$img = aq_resize($url, $thumb_x, $thumb_y, true);
								}
	
								$alt = get_post_meta($attachment->ID, '_wp_attachment_image_alt', true);
								$image_title = $attachment->post_title;

								if ( $k == 0 ) {
									$output .= '<figure class="featured-thumbnail thumbnail '.$thumbs_class.'">';
									$output .= '<a href="'.get_permalink($post->ID).'" title="'.get_the_title($post->ID).'">';
									$output .= '<img  src="'.$img.'" alt="'.get_the_title($post->ID).'" />';
								}
								$output .= '</a></figure>';
								break;
							}
						} elseif (has_post_thumbnail($post->ID)) {
							$output .= '<figure class="featured-thumbnail thumbnail '.$thumbs_class.'">';
							$output .= '<a href="'.get_permalink($post->ID).'" title="'.get_the_title($post->ID).'">';
							if (($thumb_width != '') && ($thumb_height != ''))
								$output .= '<img src="'.$image.'" alt="'.get_the_title($post->ID).'" />';
							else {
								if ($thumbs == 'normal') {
									$output .= get_the_post_thumbnail($post->ID);
								} else {
									$output .= '<img src="'.$image.'" alt="'.get_the_title($post->ID).'" />';
								}
							}
							$output .= '</a></figure>';
						}
					} else {

						// for Video and Audio post format - no lightbox
						$output .= '<figure class="featured-thumbnail thumbnail '.$thumbs_class.'"><a href="'.get_permalink($post->ID).'" title="'.get_the_title($post->ID).'">';
						if (($thumb_width != '') && ($thumb_height != ''))
							$output .= '<img src="'.$image.'" alt="'.get_the_title($post->ID).'" />';
						else {
							if ($thumbs == 'normal') {
								$output .= get_the_post_thumbnail($post->ID);
							} else {
								$output .= '<img src="'.$image.'" alt="'.get_the_title($post->ID).'" />';
							}
						}
						$output .= '</a></figure>';
					}

					// post content
					if ($post_content != 'none' || $link == 'yes') {
						$output .= '<div class="post_content">';

						switch ($post_content){
							case 'excerpt':
								$output .= '<p class="excerpt">';
									$output .= my_string_limit_words(get_the_excerpt(), 50);
								$output .= '</p>';
								break;
							case 'content':
								$output .= '<div class="full-post-content">';
									$output .= get_the_content();
								$output .= '</div>';
								break;
							case 'none':
								break;
							
						}
						if($link == 'yes'){
							$output .= '<a href="'.get_permalink($post->ID).'" class="btn btn-primary" title="'.get_the_title($post->ID).'">';
							$output .= $link_text;
							$output .= '</a>';
						}
						$output .= '</div>';
					}

					//post footer
					if ($tags == 'yes') {
						$posttags = get_the_tags();
						if ($posttags) {
							$output .= '<footer class="post_footer">'.theme_locals('tags').": ";
							  foreach($posttags as $tag) {
							    $output .= '<a href="'.get_tag_link($tag->term_id).'" rel="tag">'.$tag->name . '</a> '; 
							 }
							 $output .= '</footer>';
						}
					}

					$output .= '</article>';
					$output .= '</div><!-- .row-fluid (end) -->';

					$i++;

			} // end foreach
		$output .= '</div><!-- .posts-list (end) -->';
		return $output;
	} 
	add_shortcode('posts_list', 'posts_list_shortcode');

}?>