<?php /* Loop Name: Faq */ ?>
<?php
	// WPML filter
	$suppress_filters = get_option('suppress_filters');
	if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
		<div id="post-<?php the_ID(); ?>" <?php post_class('page'); ?>>
			<?php the_content(); ?>
			<div class="clear"></div>
		</div><!--post-->
	<?php endwhile;
	//query
	$args = array(
		'post_type'        => 'faq',
		'showposts'        => -1,
		'suppress_filters' => $suppress_filters,
		);
	$faq_query = new WP_Query( $args );

	if ( $faq_query->have_posts() ) : ?>
	<dl class="faq-list">
	<?php while ( $faq_query->have_posts() ) : $faq_query->the_post(); ?>
		<dt class="faq-list_h">
			<h4 class="marker"><?php echo theme_locals("q"); ?></h4>
			<h4><?php the_title(); ?></h4>
		</dt>
		<dd id="post-<?php the_ID(); ?>" class="faq-list_body">
			<h4 class="marker"><?php echo theme_locals("a"); ?></h4>
			<?php the_content(); ?>
		</dd>
	<?php endwhile; ?>
	</dl>

<?php else: ?>

<div class="no-results">
	<?php echo '<p><strong>' . theme_locals("there_has") . '</strong></p>'; ?>
	<p><?php echo theme_locals("we_apologize"); ?> <a href="<?php echo home_url(); ?>/" title="<?php bloginfo('description'); ?>"><?php echo theme_locals("return_to"); ?></a> <?php echo theme_locals("search_form"); ?></p>
	<?php get_search_form(); /* outputs the default Wordpress search form */ ?>
</div><!--.no-results-->

<?php endif;
	wp_reset_postdata();
?>