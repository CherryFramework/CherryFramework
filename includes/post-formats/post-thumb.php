<?php 
	$post_image_size = (!is_singular()) ? of_get_option('post_image_size') : of_get_option('single_image_size');
	$post_image_before = (!is_singular()) ? '<a href="'.get_permalink().'" title="'.get_the_title().'" >' : '';
	$post_image_after = (!is_singular()) ? '</a>' : '';
	$thumb        = get_post_thumbnail_id(); //get img ID
	$img_url      = wp_get_attachment_url($thumb, 'full'); //get img URL
	$img_width    = 770; //set width large img
	$img_height   = 380; //set height large img
	$figure_class = "large";
	$img_attr = (of_get_option('load_image') == 'false' || of_get_option('load_image')=="")?'src="':'src="//" data-src="';

	if($post_image_size=='' && has_post_thumbnail() || $post_image_size=='normal' && has_post_thumbnail()){
		$imgdata      = explode(' ', get_the_post_thumbnail());
		$img_width    = intval(substr($imgdata[1], stripos($imgdata[1], '"')+1, strrpos($imgdata[1], '"')-1));
		$img_height   = intval(substr($imgdata[2], stripos($imgdata[2], '"')+1, strrpos($imgdata[2], '"')-1));
		$figure_class = "";
	}
	
	$image = $img_attr.aq_resize($img_url, $img_width, $img_height, true).'"'; //resize & crop img
	if(has_post_thumbnail()) {
		echo '<figure class="featured-thumbnail thumbnail '.$figure_class.'" >'.$post_image_before.'<img '.$image.' alt="'.get_the_title().'" >'.$post_image_after.'</figure>';
	};
?>