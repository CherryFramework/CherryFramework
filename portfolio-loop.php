<?php // Theme Options vars
	$folio_filter = of_get_option('folio_filter');
	$category_value = get_post_meta($post->ID, 'tz_category_include', true);
?>
<div class="page_content">
	<?php the_content(); ?>
</div>
<?php
if ( !$category_value ) {
	switch ($folio_filter) {
		case 'cat': ?>
			<div class="filter-wrapper clearfix">
				<div class="pull-right">
					<strong><?php echo theme_locals("categories"); ?>: </strong>
					<ul id="filters" class="filter nav nav-pills">
						<?php
							$count_posts = wp_count_posts('portfolio');
						?>
						<li class="active"><a href="#" data-count="<?php echo $count_posts->publish; ?>" data-filter><?php echo theme_locals("show_all"); ?></a></li>
						<?php 
							$filter_array = array();
							$portfolio_categories = get_categories(array('taxonomy'=>'portfolio_category'));
							foreach($portfolio_categories as $portfolio_category) {
								$filter_array[$portfolio_category->slug] = $portfolio_category->count;
							}
							
							$the_query = new WP_Query();
							if ($paged == 0)
								$paged = 1;
							$custom_count = ($paged - 1) * $items_count;
							$the_query->query('post_type=portfolio&showposts='. $items_count .'&offset=' . $custom_count);
							while( $the_query->have_posts() ) :
								$the_query->the_post();
								$post_id = $the_query->post->ID;
								$terms = get_the_terms( $post_id, 'portfolio_category');
								if ( $terms && ! is_wp_error( $terms ) ) {
									foreach ( $terms as $term )
										$filter_array[$term->slug] = $term;
								}
							endwhile;
							foreach ($filter_array as $key => $value)
								if ( isset($value->count) ) {
									echo '<li><a href="#" data-count="'. $value->count .'" data-filter=".'.$key.'">' . $value->name . '</a></li>';
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
							$count_posts = wp_count_posts('portfolio');
						?>
						<li class="active"><a href="#" data-count="<?php echo $count_posts->publish; ?>" data-filter><?php echo theme_locals("show_all"); ?></a></li>
						<?php 
							$filter_array = array();
							$portfolio_tags = get_terms('portfolio_tag');
							foreach($portfolio_tags as $portfolio_tag) {
								$filter_array[$portfolio_tag->slug] = $portfolio_tag->count;
							}

							$the_query = new WP_Query();
							if ($paged == 0) {
								$paged = 1;
							}
							$custom_count = ($paged - 1) * $items_count;
							$the_query->query('post_type=portfolio&showposts='. $items_count .'&offset=' . $custom_count);
							while( $the_query->have_posts() ) :
								$the_query->the_post();
								$post_id = $the_query->post->ID;
								$terms = get_the_terms( $post_id, 'portfolio_tag');
								if ( $terms && ! is_wp_error( $terms ) ) {
									foreach ( $terms as $term )
										$filter_array[$term->slug] = $term;
								}
							endwhile;
							foreach ($filter_array as $key => $value)
								if ( isset($value->count) ) {
									echo '<li><a href="#" data-count="'. $value->count .'" data-filter=".'.$key.'">' . $value->name . '</a></li>';
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
	$temp = $wp_query;
	$category_include = '';
	if ( isset($category_include) ) {
		$category_include = '&portfolio_category='. $category_value;
	}
	$wp_query = null;
	$wp_query = new WP_Query();
	$wp_query->query("post_type=portfolio&paged=".$paged.'&showposts='.$items_count . $category_include); 
?>
	
<?php if ( ! have_posts() ) : ?>
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
	$wp_query = null;
	$wp_query = $temp;
?>