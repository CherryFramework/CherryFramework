<?php
/**
 * Categories
 *
 */
if (!function_exists('categories_shortcode')) {

	function categories_shortcode($atts, $content = null) {
		extract(shortcode_atts(
			array(
				'type'  => '',
				'class' => 'check'
			), $atts));

		$taxonomy_value = '';

		if (!empty($type))
			$taxonomy_value = $type . '_';

		if (empty($class)) {
			$class = 'categories';
		}

		$args = array(
			'type'     => 'post',
			'taxonomy' => $taxonomy_value . 'category'
		);

		$categories = get_categories($args); 
		$output = '<div class="list styled '.$class.'-list">';
		$output .= '<ul>';
		foreach ($categories as $category) {
			$output .= '<li>';
			$output .= '<a href="' . get_category_link( $category ) . '" title="' . $category->slug . '" ' . '>' . $category->name.'</a>';
			$output .= '</li>';
		}
		$output .= '</ul>';
		$output .= '</div>';

		return $output;
	}
	add_shortcode('categories', 'categories_shortcode');
	
}?>