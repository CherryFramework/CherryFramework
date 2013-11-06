<?php
if (!function_exists('shortcode_carousel_owl')) {
	function shortcode_carousel_owl($args) {
		extract(shortcode_atts(array(
				'title'              => '',
				'posts_count'        => 10,
				'post_type'          => 'blog',
				'post_status'        => 'publish',
				'visibility_items'   => 5,
				'thumb'              => 'yes',
				'thumb_width'        => 220,
				'thumb_height'       => 180,
				'more_text_single'   => '',
				'categories'         => '',
				'excerpt_count'      => 15,
				'date'               => 'yes',
				'author'             => 'yes',
				'auto_play'          => 0,
				'display_navs'       => 'yes',
				'display_pagination' => 'yes',
				'custom_class'       => ''
		), $args));

		$random_ID = rand();
		$thumb = $thumb == 'yes' ? true : false ;
		$date = $date == 'yes' ? true : false ;
		$author = $author == 'yes' ? true : false ;
		$display_navs = $display_navs == 'yes' ? 'true' : 'false' ;
		$display_pagination = $display_pagination == 'yes' ? 'true' : 'false' ;

		switch (strtolower(str_replace(' ', '-', $post_type))) {
			case 'blog':
				$post_type = 'post';
				break;
			case 'portfolio':
				$post_type = 'portfolio';
				break;
			case 'testimonial':
				$post_type = 'testi';
				break;
			case 'services':
				$post_type = 'services';
				break;
			case 'our-team':
				$post_type = 'team';
			break;
		}

		$get_category_type = $post_type == 'post' ? 'category' : $post_type.'_category' ;
		$categories_ids = array();
		foreach (explode(',', str_replace(', ', ',', $categories)) as $category) {
			$get_cat_id = get_term_by('name', $category, $get_category_type);
			if($get_cat_id){
				$categories_ids[] = $get_cat_id->term_id;
			}
		}
		$get_query_tax = $categories_ids ? 'tax_query' : '' ;

		if($posts_count!=0){
			$suppress_filters = get_option('suppress_filters'); // WPML filter
			$args = array(
				'post_status' => $post_status,
				'posts_per_page' => $posts_count,
				'ignore_sticky_posts' => 1,
				'post_type' => $post_type,
				'suppress_filters' => $suppress_filters,
				"$get_query_tax" => array(
									array(
										'taxonomy' => $get_category_type,
										'field' => 'id',
										'terms' => $categories_ids
										)
								)
			);
			$output = '<div class="carousel-wrap '.$custom_class.'">';
			$output .= $title ? '<h2>'.$title.'</h2>' : '' ;
			$output .= '<div id="owl-carousel-'.$random_ID.'" class="owl-carousel-'. $post_type .' owl-carousel" data-items="'.$visibility_items.'" data-auto-play="'.$auto_play.'" data-nav="'.$display_navs.'" data-pagination="'.$display_pagination.'">';

			query_posts($args);
			if ( have_posts() ) :
				while ( have_posts() ) : the_post();
					// get post thumbnail
					$thumb = $thumb && has_post_thumbnail() ? wp_get_attachment_url(get_post_thumbnail_id(), 'full') : false ;
					$image = aq_resize($thumb, $thumb_width, $thumb_height, true) or $thumb;
					// get post excerpt
					$excerpt = get_the_excerpt();

					$output .= '<div class="item">';
						$output .= $thumb ?'<figure><a href="'.get_permalink().'"  alt="'.get_the_title().'"><img data-src="'.$image.'" alt="'.get_the_title().'"></a></figure>' : '' ;
						$output .= '<div class="desc">';
							$output .= $date ? '<time datetime="'.get_the_time('Y-m-d\TH:i:s').'">' .get_the_date().'</time>' : '' ;
							$output .= $author ? '<em class="author">, '.theme_locals("by").' <a href="'.get_author_posts_url(get_the_author_meta( 'ID' )).'">'.get_the_author_meta('display_name').'</a></em>' : '' ;
							$output .= '<h5><a href="'.get_permalink().'" title="'.get_the_title().'">'.get_the_title().'</a></h5>';
							$output .= $excerpt_count > 0 ? '<p class="excerpt">'.my_string_limit_words($excerpt, $excerpt_count).'</p>' : '' ;
							$output .= $more_text_single ? '<a href="'.get_permalink().'" class="btn btn-primary" title="'.get_the_title().'">'.$more_text_single.'</a>': '' ;
						$output .= '</div>';
					$output .= '</div>';
				endwhile;
			endif;
			$output .= '</div></div>';
			wp_reset_query();
			echo $output;
		}
	}
	add_shortcode('carousel_owl', 'shortcode_carousel_owl');
}
?>