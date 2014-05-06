<?php // Theme Options vars
	$folio_filter         = of_get_option('folio_filter');
	$category_value       = get_post_meta($post->ID, 'tz_category_include', true);
	$folio_filter_orderby = ( of_get_option('folio_filter_orderby') ) ? of_get_option('folio_filter_orderby') : 'name';
	$folio_filter_order   = ( of_get_option('folio_filter_order') ) ? of_get_option('folio_filter_order') : 'name';

	// WPML filter
	$suppress_filters = get_option('suppress_filters');
?>
<div class="page_content">
	<?php the_content(); ?>
	<div class="clear"></div>
</div>
<?php
if ( post_password_required() ) {
	return;
}
if ( !$category_value ) {
	switch ($folio_filter) {
		case 'cat': ?>
			<div class="filter-wrapper clearfix">
				<div class="pull-right">
					<strong><?php echo theme_locals("categories"); ?>: </strong>
					<ul id="filters" class="filter nav nav-pills">
						<?php
							// query
							$args = array(
								'post_type'        => 'portfolio',
								'posts_per_page'   => -1,
								'post_status'      => 'publish',
								'orderby'          => 'name',
								'order'            => 'ASC',
								'suppress_filters' => $suppress_filters
								);
							$portfolio_posts = get_posts($args);

							foreach( $portfolio_posts as $k => $portfolio ) {
								//Check if WPML is activated
								if ( defined( 'ICL_SITEPRESS_VERSION' ) ) {
									global $sitepress;

									$post_lang = $sitepress->get_language_for_element($portfolio->ID, 'post_portfolio');
									$curr_lang = $sitepress->get_current_language();
									// Unset not translated posts
									if ( $post_lang != $curr_lang ) {
										unset( $portfolio_posts[$k] );
									}
									// Post ID is different in a second language Solution
									if ( function_exists( 'icl_object_id' ) ) {
										$portfolio = get_post( icl_object_id( $portfolio->ID, 'portfolio', true ) );
									}
								}
							}
							$count_posts = count($portfolio_posts);
						?>
						<li class="active"><a href="#" data-count="<?php echo $count_posts; ?>" data-filter><?php echo theme_locals("show_all"); ?></a></li>
						<?php
							$filter_array = array();
							$portfolio_categories = get_categories( array(
								'taxonomy' => 'portfolio_category',
								'orderby'  => $folio_filter_orderby,
								'order'    => $folio_filter_order,
								)
							);
							foreach($portfolio_categories as $portfolio_category) {
								$filter_array[$portfolio_category->name] = $portfolio_category->count;
							}

							if ($paged == 0) $paged = 1;
							$custom_count = ($paged - 1) * $items_count;

							// query
							$args = array(
								'post_type'        => 'portfolio',
								'showposts'        => $items_count,
								'offset'           => $custom_count,
								'suppress_filters' => $suppress_filters,
								);
							$the_query = new WP_Query($args);

							while( $the_query->have_posts() ) :
								$the_query->the_post();
								$post_id = $the_query->post->ID;
								$terms = get_the_terms( $post_id, 'portfolio_category');
								if ( $terms && ! is_wp_error( $terms ) ) {
									foreach ( $terms as $term ) {
										$filter_array[$term->name] = $term;
									}
								}
							endwhile;

							foreach ($filter_array as $key => $value) {
								if ( isset($value->count) ) {
									echo '<li><a href="#" data-count="'. $value->count .'" data-filter=".term_id_'.$value->term_id.'">' . $value->name . '</a></li>';
								}
							}
							wp_reset_postdata();
						?>
					</ul>
					<div class="clear"></div>
				</div>
			</div>
			<?php
			break;
		case 'tag': ?>
			<div class="filter-wrapper clearfix">
				<div class="pull-right">
					<strong><?php echo theme_locals("tags"); ?>: </strong>
					<ul id="tags" class="filter nav nav-pills">
						<?php
							// query
							$args = array(
								'post_type'        => 'portfolio',
								'posts_per_page'   => -1,
								'post_status'      => 'publish',
								'orderby'          => 'name',
								'order'            => 'ASC',
								'suppress_filters' => $suppress_filters
								);
							$portfolio_posts = get_posts($args);

							foreach( $portfolio_posts as $k => $portfolio ) {
								// Unset not translated posts
								if ( function_exists( 'wpml_get_language_information' ) ) {
									global $sitepress;

									$check               = wpml_get_language_information( $portfolio->ID );
									$language_code = substr( $check['locale'], 0, 2 );
									if ( $language_code != $sitepress->get_current_language() ) unset( $portfolio_posts[$k] );

									// Post ID is different in a second language Solution
									if ( function_exists( 'icl_object_id' ) ) $portfolio = get_post( icl_object_id( $portfolio->ID, 'portfolio', true ) );
								}
							}
							$count_posts = count($portfolio_posts);
						?>
						<li class="active"><a href="#" data-count="<?php echo $count_posts; ?>" data-filter><?php echo theme_locals("show_all"); ?></a></li>
						<?php
							$filter_array = array();
							$portfolio_tags = get_terms( 'portfolio_tag', array(
								'orderby'  => $folio_filter_orderby,
								'order'    => $folio_filter_order,
								)
							);
							foreach($portfolio_tags as $portfolio_tag) {
								$filter_array[$portfolio_tag->slug] = $portfolio_tag->count;
							}

							if ($paged == 0) $paged = 1;
							$custom_count = ($paged - 1) * $items_count;

							// query
							$args = array(
								'post_type'        => 'portfolio',
								'showposts'        => $items_count,
								'offset'           => $custom_count,
								'suppress_filters' => $suppress_filters
								);
							$the_query = new WP_Query($args);

							while( $the_query->have_posts() ) :
								$the_query->the_post();
								$post_id = $the_query->post->ID;
								$terms = get_the_terms( $post_id, 'portfolio_tag');
								if ( $terms && ! is_wp_error( $terms ) ) {
									foreach ( $terms as $term ) {
										$filter_array[$term->slug] = $term;
									}
								}
							endwhile;

							foreach ($filter_array as $key => $value) {
								if ( isset($value->count) ) {
									echo '<li><a href="#" data-count="'. $value->count .'" data-filter=".term_id_'.$value->term_id.'">' . $value->name . '</a></li>';
								}
							}
							wp_reset_postdata();
						?>
					</ul>
					<div class="clear"></div>
				</div>
			</div>
			<?php
			break;
		default:
			break;
	}
}?>

<?php
	// http://codex.wordpress.org/Pagination#Adding_the_.22paged.22_parameter_to_a_query
	if ( get_query_var('paged') ) {
		$paged = get_query_var('paged');
	} elseif ( get_query_var('page') ) {
		$paged = get_query_var('page');
	} else {
		$paged = 1;
	}

	// Get Order & Orderby Parameters
	$orderby = ( of_get_option('folio_posts_orderby') ) ? of_get_option('folio_posts_orderby') : 'date';
	$order   = ( of_get_option('folio_posts_order') ) ? of_get_option('folio_posts_order') : 'DESC';

	// The Query
	$args = array(
		'post_type'          => 'portfolio',
		'paged'              => $paged,
		'showposts'          => $items_count,
		'portfolio_category' => $category_value,
		'suppress_filters'   => $suppress_filters,
		'orderby'            => $orderby,
		'order'              => $order,
		);
	global $query_string;
	query_posts($args);
?>

<?php if ( !have_posts() ) : ?>
	<div id="post-0" class="post error404 not-found">
		<h1 class="entry-title"><?php echo theme_locals("not_found"); ?></h1>
		<div class="entry-content">
			<p><?php echo theme_locals("apologies"); ?></p>
			<?php get_search_form(); ?>
		</div><!-- .entry-content -->
	</div><!-- #post-0 -->
<?php endif; ?>

<ul id="portfolio-grid" class="filterable-portfolio thumbnails portfolio-<?php echo $cols; ?>" data-cols="<?php echo $cols; ?>">
	<?php get_template_part('filterable-portfolio-loop'); ?>
</ul>

<?php
	get_template_part('includes/post-formats/post-nav');
	wp_reset_query();
?>