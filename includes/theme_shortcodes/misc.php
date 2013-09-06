<?php
/**
 * Misc
 */
 
//Close
function shortcode_close_icon($atts, $content = null) {
	extract(shortcode_atts(
			array(
				'dismiss' => 'alert'
			), $atts));
	
	 $output = '<a class="close" href="#" data-dismiss="'.$dismiss.'">&times;</a>';
	 return $output;
}
add_shortcode('close', 'shortcode_close_icon');


//Well
function shortcode_well($atts, $content = null) {
	extract(shortcode_atts(
			array(
				'size' => 'normal'
			), $atts));
	
	 $output = '<div class="well '.$size.'">';
	 $output .= do_shortcode($content);
	 $output .= '</div>';
	 
	 return $output;
}
add_shortcode('well', 'shortcode_well');


//Small
function shortcode_small($args, $content) {
	return '<small>'.do_shortcode($content).'</small>';
}
add_shortcode('small', 'shortcode_small');


// Title Box
if (!function_exists('title_shortcode')) {

	function title_shortcode($atts, $content = null) {
		extract(shortcode_atts(
			array(
				'title'        => '',
				'subtitle'     => '',
				'icon'         => '',
				'custom_class' => ''
			), $atts));

		// get site URL
		$home_url = home_url();
	 
	    $output =  '<div class="title-box clearfix '.$custom_class.'">';
	 
		if ($icon!="") {
			$output .= '<span class="title-box_icon">';
			$output .= '<img src="' . $home_url . '/' . $icon .'" alt="" />';
			$output .= '</span>';
		}
	 
			$output .= '<h2 class="title-box_primary">';
			$output .= $title;
			$output .= '</h2>';
	 
		if ($subtitle!="") {
			$output .= '<h3 class="title-box_secondary">';
			$output .= $subtitle;
			$output .= '</h3>';
		}
	 
			$output .= '</div><!-- //.title-box -->';
	 
			return $output;
	} 
	add_shortcode('title_box', 'title_shortcode');
}
	// Shortcode site map
	if (!function_exists('shortcode_site_map')) {
		function shortcode_site_map($atts, $content = null) {
			extract(shortcode_atts(array(
				'title' => '',
				'type' => 'Lines',
				'custom_class' => ''
			), $atts));

			$title = ($title!='') ? '<h2 class="site_map_title"><span class="icon-sitemap"></span>'.$title.'</h2>' : '' ;
			$args=array('public'   => true, '_builtin' => false); 
			$post_types=get_post_types($args,'names', 'or'); 
			
			$sort_array = array('page' => '', 'post' => '', 'services' => '', 'portfolio' => '', 'slider' => '', 'team' => '', 'testi' => '', 'faq' => '');
			$post_types = array_merge($sort_array, $post_types);
			unset($post_types['attachment'], $post_types['wpcf7_contact_form']);
			$span_counter=0;
			$wrapp_class = ($type!='Lines') ? 'group' : '';
			$item_class = ($type!='Lines') ? 'grid  clearfix' : 'line clearfix';
			$output = '<div class="site_map '.$custom_class.' clearfix">'.$title;

			foreach( $post_types as $post_type ) {
				if(!empty($post_type)){
					$output .= ($span_counter==0 && $type!='line') ? '<div class="'.$wrapp_class.'">' : '' ;
					//var_dump($post_type);
				   	$pt = get_post_type_object( $post_type );
				  	$output .= '<div class="'.$item_class.'"><h2>'.$pt->labels->name.'</h2><ul>';

				   	query_posts('post_type='.$post_type.'&posts_per_page=-1&orderby=title&order=ASC');
				   	if ( have_posts() ) while( have_posts() )  {
				     	the_post();
				     	$output .= '<li><a href="'.get_permalink().'">'.get_the_title().'</a></li>';
				   	}
					wp_reset_query();
				   	if($span_counter>2 && $type!='line'){
						$span_counter=0;
						$output .= '</div>';
				   	}else{
				   		$span_counter++;
				   	}
					$output .= '</ul></div>';
				}
			}
			$output .= '</div>';

			return $output;
		}
		add_shortcode('site_map', 'shortcode_site_map');
	}
?>