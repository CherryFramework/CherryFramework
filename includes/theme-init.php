<?php
add_action( 'after_setup_theme', 'cherry_setup' );

if ( !function_exists( 'cherry_setup' ) ) :

	function cherry_setup() {

		// This theme styles the visual editor with editor-style.css to match the theme style.
		add_editor_style();

		// Add support for Post Thumbnails.
		add_theme_support( 'post-thumbnails' );
		set_post_thumbnail_size( 200, 150, true ); // Normal post thumbnails
		add_image_size( 'slider-post-thumbnail', 940, 446, true ); // Slider Thumbnail
		add_image_size( 'slider-thumb', 100, 50, true ); // Slider Small Thumbnail

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		// Add custom menu support.
		add_theme_support( 'menus' );
		register_nav_menus(
			array(
				'header_menu' => theme_locals("header_menu"),
				'footer_menu' => theme_locals("footer_menu"),
			)
		);

	}

endif;

/* Slider */
function my_post_type_slider() {
	register_post_type( 'slider',
		array(
			'label'               => theme_locals("slides"),
			'singular_label'      => theme_locals("slides"),
			'_builtin'            => false,
			'exclude_from_search' => true, // Exclude from Search Results
			'capability_type'     => 'page',
			'public'              => true,
			'show_ui'             => true,
			'show_in_nav_menus'   => false,
			'rewrite' => array(
							'slug'       => 'slide-view',
							'with_front' => FALSE,
						),
			'query_var' => 'slide', // This goes to the WP_Query schema
			'menu_icon' => ( version_compare( $GLOBALS['wp_version'], '3.8', '>=' ) ) ? 'dashicons-slides' : PARENT_URL . '/includes/images/icon_slides.png',
			'supports'  => array(
								'title',
								'thumbnail',
							)
		)
	);
}
add_action('init', 'my_post_type_slider');

/* Portfolio */
function my_post_type_portfolio() {
	register_post_type( 'portfolio',
		array(
			'label'             => theme_locals("portfolio"),
			'singular_label'    => theme_locals("portfolio"),
			'_builtin'          => false,
			'public'            => true,
			'show_ui'           => true,
			'show_in_nav_menus' => true,
			'hierarchical'      => true,
			'capability_type'   => 'page',
			'menu_icon'         => ( version_compare( $GLOBALS['wp_version'], '3.8', '>=' ) ) ? 'dashicons-portfolio' : PARENT_URL . '/includes/images/icon_portfolio.png',
			'rewrite'           => array(
										'slug'       => 'portfolio-view',
										'with_front' => FALSE,
									),
			'supports' => array(
								'title',
								'editor',
								'thumbnail',
								'excerpt',
								'comments',
							)
		)
	);
	register_taxonomy(
		'portfolio_category',
		'portfolio',
		array(
			'hierarchical'  => true,
			'label'         => theme_locals("categories"),
			'singular_name' => theme_locals("category"),
			'rewrite'       => true,
			'query_var'     => true
		)
	);
	register_taxonomy(
		'portfolio_tag',
		'portfolio',
		array(
			'hierarchical'  => false,
			'label'         => theme_locals("tags"),
			'singular_name' => theme_locals("tag"),
			'rewrite'       => true,
			'query_var'     => true
		)
	);
}
add_action('init', 'my_post_type_portfolio');

/* Testimonial */
function my_post_type_testi() {
	register_post_type( 'testi',
		array(
			'label'             => theme_locals("testimonial"),
			'public'            => true,
			'show_ui'           => true,
			'show_in_nav_menus' => false,
			'menu_position'     => 5,
			'menu_icon'         => ( version_compare( $GLOBALS['wp_version'], '3.8', '>=' ) ) ? 'dashicons-testimonial' : '',
			'rewrite'           => array(
										'slug'       => 'testimonial-view',
										'with_front' => FALSE,
									),
			'supports' => array(
								'title',
								'thumbnail',
								'editor',
							)
		)
	);
}
add_action('init', 'my_post_type_testi');

/* Services */
function my_post_type_services() {
	register_post_type( 'services',
		array(
			'label'             => theme_locals("services"),
			'public'            => true,
			'show_ui'           => true,
			'show_in_nav_menus' => false,
			'menu_position'     => 5,
			'rewrite'           => array(
										'slug'       => 'services-view',
										'with_front' => FALSE,
									),
			'supports' => array(
								'title',
								'thumbnail',
								'editor',
							)
		)
	);
}
add_action('init', 'my_post_type_services');

/* FAQs */
function phi_post_type_faq() {
	register_post_type('faq',
		array(
			'label'               => theme_locals("faqs"),
			'singular_label'      => theme_locals("faqs"),
			'public'              => false,
			'show_ui'             => true,
			'_builtin'            => false, // It's a custom post type, not built in
			'_edit_link'          => 'post.php?post=%d',
			'capability_type'     => 'post',
			'hierarchical'        => false,
			'rewrite'             => array('slug' => 'faq'), // Permalinks
			'query_var'           => 'faq', // This goes to the WP_Query schema
			'menu_position'       => 5,
			'menu_icon'           => ( version_compare( $GLOBALS['wp_version'], '3.8', '>=' ) ) ? 'dashicons-editor-help' : '',
			'publicly_queryable'  => true,
			'exclude_from_search' => false,
			'supports'            => array(
										'title',
										'author',
										'editor',
									),
		)
	);
}
add_action('init', 'phi_post_type_faq');

/* Our Team */
function my_post_type_team() {
	register_post_type( 'team',
		array(
			'label'               => theme_locals("our_team"),
			'singular_label'      => theme_locals("our_team"),
			'_builtin'            => false,
			// 'exclude_from_search' => true, // Exclude from Search Results
			'capability_type'     => 'page',
			'public'              => true,
			'show_ui'             => true,
			'show_in_nav_menus'   => false,
			'menu_position'       => 5,
			'menu_icon'           => ( version_compare( $GLOBALS['wp_version'], '3.8', '>=' ) ) ? 'dashicons-businessman' : '',
			'rewrite'             => array(
										'slug'       => 'team-view',
										'with_front' => FALSE,
									),
			'supports' => array(
							'title',
							'editor',
							'thumbnail',
						)
		)
	);
}
add_action('init', 'my_post_type_team');
?>