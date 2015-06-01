<?php
	if ( has_post_thumbnail() ) {

		$thumb             = get_post_thumbnail_id(); // get img ID
		$img_url           = wp_get_attachment_url( $thumb, 'full' ); // get img URL
		$blog_layout_type  = of_get_option( 'blog_sidebar_pos' );
		$img_width         = ( !is_singular() && 'masonry' == $blog_layout_type ) ? 450 : 900; // set width large img
		$img_height        = ( !is_singular() && 'masonry' == $blog_layout_type ) ? 222 : 444; // set height large img
		$figure_class      = "large";

		$post_format       = get_post_format();
		$module_box_atter  = ( $post_format == 'image' ) ? 'rel="prettyPhoto" ' : '';
		$post_href         = ( $post_format == 'image' ) ? $img_url : get_permalink();

		$post_image_size   = ( !is_singular() ) ? of_get_option( 'post_image_size' ) : of_get_option( 'single_image_size' );

		// if ( $post_image_size == '' || $post_image_size == 'normal' && $blog_layout_type != 'masonry' ) {
		if ( $post_image_size == '' || $post_image_size == 'normal' ) {
			$imgdata      = explode( ' ', get_the_post_thumbnail() );
			$img_width    = intval( substr( $imgdata[1], stripos($imgdata[1], '"' )+1, strrpos( $imgdata[1], '"' )-1));
			$img_height   = intval( substr( $imgdata[2], stripos($imgdata[2], '"' )+1, strrpos( $imgdata[2], '"' )-1));
			$figure_class = '';
		}

		$img_attr = ( of_get_option('load_image') == 'false' || of_get_option('load_image') == '' ) ? 'src="' : 'src="//" data-src="';
		$post_image_before = ( !is_singular() || $post_format == 'image' ) ? '<a ' . $module_box_atter . 'href="' . $post_href . '" title="' . get_the_title() . '" >' : '';
		$post_image_after  = ( !is_singular() || $post_format == 'image' ) ? '</a>' : '';

		$image = $img_attr . aq_resize( $img_url, $img_width, $img_height, true ) . '"'; // resize & crop img
		echo '<figure class="featured-thumbnail thumbnail ' . $figure_class.'" >' . $post_image_before . '<img ' . $image . ' alt="' . get_the_title() . '" >' . $post_image_after . '</figure>';
	}
?>