<section class="title-section">
	<h1 class="title-header">

	<?php $shop_page = false;

		if ( function_exists( 'is_shop' ) ) {

			if ( is_shop() )
				$shop_page = true;
		}

		$title = '';

		if ( is_home() && !is_front_page() ) {

			$blog_text = apply_filters( 'cherry_text_translate', of_get_option('blog_text'), 'blog_text' );

			if ( $blog_text )
				$title = $blog_text;

			else
				$title = get_post_field( 'post_title', get_queried_object_id() );
		}

		elseif ( is_category() )
			$title = single_cat_title( '', false );

		elseif ( is_tag() )
			$title = single_tag_title( '', false );

		elseif ( is_tax() )
			$title = single_term_title( '', false );

		elseif ( is_author() )
			$title = get_the_author_meta( 'display_name', get_query_var( 'author' ) );

		elseif ( is_search() )
			$title = cherry_search_title( theme_locals("fearch_for"), false );

		elseif ( is_day() )
			$title = cherry_single_day_title( theme_locals("daily_archives"), false );

		elseif ( is_month() )
			$title = single_month_title( ' ', false );

		elseif ( is_year() )
			$title = cherry_single_year_title( theme_locals("yearly_archives"), false );

		elseif ( is_archive() )
			$title = cherry_single_archive_title( theme_locals("archives"), false );

		elseif ( $shop_page ) {

			if ( class_exists( 'Woocommerce' ) && !is_single() )
				$page_id = woocommerce_get_page_id('shop');

			elseif ( function_exists( 'jigoshop_init' ) && !is_singular() )
				$page_id = jigoshop_get_page_id('shop');

			$title = get_page( $page_id )->post_title;

		} else {

			$pagetitle = get_post_custom_values( 'page-title' );

			if ( empty( $pagetitle ) )
				$title = the_title( '', '', false );
			else
				$title = $pagetitle[0];

		}

		/**
		 * Filters title for page.
		 *
		 * @since 3.1.5
		 */
		$title = apply_filters( 'cherry_page_title', $title );

		echo $title;

		/**
		 * Fires before tag <h1> close.
		 *
		 * @since 3.1.5
		 */
		do_action( 'cherry_get_title_desc' ); ?>
	</h1>

	<?php if ( of_get_option('g_breadcrumbs_id') == 'yes' ) { // Begin breadcrumbs

			if ( ( function_exists( 'is_shop' ) && is_shop() ) || ( function_exists( 'is_product' ) && is_product() ) ) { // Begin shop

				if ( class_exists( 'Woocommerce' ) )
					woocommerce_breadcrumb( apply_filters( 'cherry_woocommerce_breadcrumb_args', array(
						'delimiter'   => ' / ',
						'wrap_before' => '<ul class="breadcrumb breadcrumb__t">',
						'wrap_after'  => '</ul>',
						) ) );

				elseif ( function_exists( 'jigoshop_init' ) ) {
					jigoshop_breadcrumb( apply_filters( 'cherry_jigoshop_breadcrumb_delimiter', ' / ' ), '<ul class="breadcrumb breadcrumb__t">', '</ul>' );
				}


			} elseif ( function_exists( 'breadcrumbs' ) ) {
				breadcrumbs();
			}
	} ?>
</section><!-- .title-section -->