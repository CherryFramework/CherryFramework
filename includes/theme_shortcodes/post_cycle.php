<?php
/**
 * Post Cycle
 *
 */
if (!function_exists('shortcode_post_cycle')) {

	function shortcode_post_cycle($atts, $content = null) {
		extract(shortcode_atts(array(
				'num' => '5',
				'type' => '',
				'meta' => '',
				'effect' => 'slide',
				'thumb' => 'true',
				'thumb_width' => '200',
				'thumb_height' => '180',
				'more_text_single' => theme_locals('read_more'),
				'category' => '',
				'custom_category' => '',				
				'excerpt_count' => '15',
				'pagination' => 'true',
				'navigation' => 'true',
				'custom_class' => ''
		), $atts));
		
		$type_post=$type;
		
		$slider_pagination=$pagination;
		
		$slider_navigation=$navigation;
		
		$random = gener_random(10);		

		$output = '<script type="text/javascript">
						$(window).load(function() {
							$("#flexslider_'.$random.'").flexslider({
								animation: "'.$effect.'",
								smoothHeight : true,
								directionNav: '.$slider_navigation.',
								controlNav: '.$slider_pagination.'
							});
						});';
		$output .= '</script>';
		$output .= '<div id="flexslider_'.$random.'" class="flexslider no-bg '.$custom_class.'">';
			$output .= '<ul class="slides">';
			
			global $post;
			global $my_string_limit_words;
			
			$args = array(
				'post_type' => $type_post,
				'category_name' => $category,
				$type_post . '_category' => $custom_category,
				'numberposts' => $num,
				'orderby' => 'post_date',
				'order' => 'DESC'
			);

			$latest = get_posts($args);
			
			foreach($latest as $post) {
				setup_postdata($post);
				$excerpt = get_the_excerpt();
				$attachment_url = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'full' );
				$url = $attachment_url['0'];
				$image = aq_resize($url, $thumb_width, $thumb_height, true);				

				$output .= '<li>';				
					
					if ($thumb == 'true') {

						if ( has_post_thumbnail($post->ID) ){
							$output .= '<figure class="thumbnail featured-thumbnail"><a href="'.get_permalink($post->ID).'" title="'.get_the_title($post->ID).'">';
							$output .= '<img  src="'.$image.'" alt="'.get_the_title($post->ID).'" />';
							$output .= '</a></figure>';
						}  else {							

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
									$img = aq_resize( $image_attributes[0], $thumb_width, $thumb_height, true ); //resize & crop img
									$alt = get_post_meta($attachment->ID, '_wp_attachment_image_alt', true);
									$image_title = $attachment->post_title;

									if ( $k == 0 ) {
										$output .= '<figure class="featured-thumbnail">';
										$output .= '<a href="'.get_permalink($post->ID).'" title="'.get_the_title($post->ID).'">';
										$output .= '<img  src="'.$img.'" alt="'.get_the_title($post->ID).'" />';
										$output .= '</a></figure>';
									} break;
									$k++;
								}					
							}
						}
					}
					
					$output .= '<h5><a href="'.get_permalink($post->ID).'" title="'.get_the_title($post->ID).'">';
					$output .= get_the_title($post->ID);
					$output .= '</a></h5>';
					$custom = get_post_custom($post->ID);
					
					if($meta == 'true'){
						$output .= '<span class="meta">';
						$output .= '<span class="post-date">';
						$output .= get_the_time( get_option( 'date_format' ) );
						$output .= '</span>';
						$output .= '<span class="post-comments">'.theme_locals('comments').": ";
						$output .= '<a href="'.get_comments_link($post->ID).'">';
						$output .= get_comments_number($post->ID);
						$output .= '</a>';
						$output .= '</span>';
						$output .= '</span>';
					}
					//display post options
					$output .= '<div class="post_options">';
					switch($type_post) {
					    case "team":
					    	$teampos = ($custom["my_team_pos"][0])?$custom["my_team_pos"][0]:"";
					    	$teaminfo = ($custom["my_team_info"][0])?$custom["my_team_info"][0]:"";
					        $output .= "<span class='page-desc'>".$teampos."</span><br><span class='team-content post-content'>".$teaminfo."</span>";
					        break;
					    case "testi":
					    	$testiname = $custom["my_testi_caption"][0]?$custom["my_testi_caption"][0]:"";
							$testiurl = $custom["my_testi_url"][0]?$custom["my_testi_url"][0]:"";
							$testiinfo = $custom["my_testi_info"][0]?$custom["my_testi_info"][0]:"";
					        $output .="<span class='user'>".$testiname."</span>, <span class='info'>".$testiinfo."</span><br><a href='".$testiurl."'>".$testiurl."</a>";
					        break;
					    case "portfolio":
				    		$portfolioClient = $custom["tz_portfolio_client"][0]?$custom["tz_portfolio_client"][0]:"";
							$portfolioDate = $custom["tz_portfolio_date"][0]?$custom["tz_portfolio_date"][0]:"";
							$portfolioInfo = $custom["tz_portfolio_info"][0]?$custom["tz_portfolio_info"][0]:"";
							$portfolioURL = $custom["tz_portfolio_url"][0]?$custom["tz_portfolio_url"][0]:"";
					        $output .="<strong class='portfolio-meta-key'>".theme_locals('client').": </strong><span> ".$portfolioClient."</span><br>";
					       	$output .="<strong class='portfolio-meta-key'>".theme_locals('date').": </strong><span> ".$portfolioDate."</span><br>";
					       	$output .="<strong class='portfolio-meta-key'>".theme_locals('info').": </strong><span> ".$portfolioInfo."</span><br>";
					       	$output .="<a href='".$portfolioURL."'>".theme_locals('launch_project')."</a><br>";
						    break;
	        			default:
	        				$output .="";
					};
					$output .= '</div>';
					
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
					
				$output .= '</li>';
			}
			$output .= '</ul>';
		$output .= '</div>';
		return $output;
	}
	add_shortcode('post_cycle', 'shortcode_post_cycle');
	
}?>