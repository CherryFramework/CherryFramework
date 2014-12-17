<?php /* Loop Name: Testi */ ?>
<?php if (have_posts()) : while (have_posts()) : the_post();
	$content = get_the_content();
	if (!empty($content)) { ?>
		<div id="page-content"><?php the_content(); ?></div>
	<?php }
endwhile; endif;

// WPML filter
$suppress_filters = get_option('suppress_filters');

// http://codex.wordpress.org/Pagination#Adding_the_.22paged.22_parameter_to_a_query
if ( get_query_var('paged') ) {
	$paged = get_query_var('paged');
} elseif ( get_query_var('page') ) {
	$paged = get_query_var('page');
} else {
	$paged = 1;
}
$args = array(
	'post_type'        => 'testi',
	'showposts'        => 4,
	'paged'            => $paged,
	'suppress_filters' => $suppress_filters,
	);
$testi_query = new WP_Query( $args );

if ( $testi_query->have_posts()) : while ( $testi_query->have_posts() ) : $testi_query->the_post();
	$testiname  = get_post_meta( $post->ID, 'my_testi_caption', true );
	$testiurl   = esc_url( get_post_meta( $post->ID, 'my_testi_url', true ) );
	$testiinfo  = get_post_meta( $post->ID, 'my_testi_info', true );
	$testiemail = sanitize_email( get_post_meta( $post->ID, 'my_testi_email', true ) );
?>
<article id="post-<?php the_ID(); ?>" class="testimonial clearfix">
	<blockquote class="testimonial_bq">
		<?php if ( has_post_thumbnail() ) {
			$thumb   = get_post_thumbnail_id();
			$img_url = wp_get_attachment_url( $thumb,'full' ); //get img URL
			$image   = aq_resize( $img_url, 120, 120, true ); //resize & crop img
		?>
		<figure class="featured-thumbnail thumbnail hidden-phone">
			<img src="<?php echo $image ?>" alt="<?php the_title(); ?>" />
		</figure>
		<?php } ?>
		<div class="testimonial_content">
			<?php the_content(); ?>
			<div class="clear"></div>
			<small>
				<?php if ( !empty( $testiname ) ) { ?>
					<span class="user"><?php echo $testiname; ?></span><?php echo ', '; ?>
				<?php } ?>
				<?php if ( !empty( $testiinfo ) ) { ?>
					<span class="info"><?php echo $testiinfo; ?></span><br>
				<?php } ?>
				<?php if ( !empty( $testiurl ) ) { ?>
					<a class="testi-url" href="<?php echo $testiurl; ?>" target="_blank"><?php echo $testiurl; ?></a><br>
				<?php } ?>
				<?php if ( !empty( $testiemail ) && is_email( $testiemail ) ) {
					echo '<a class="testi-email" href="mailto:' . antispambot( $testiemail, 1 ) . '">' . antispambot( $testiemail ) . ' </a>';
				} ?>
			</small>
		</div>
	</blockquote>
</article>

<?php endwhile; else: ?>

<div class="no-results">
	<?php echo '<p><strong>' . theme_locals("there_has") . '</strong></p>'; ?>
	<p><?php echo theme_locals("we_apologize"); ?> <a href="<?php echo home_url(); ?>/" title="<?php bloginfo('description'); ?>"><?php echo theme_locals("return_to"); ?></a> <?php echo theme_locals("search_form"); ?></p>
	<?php get_search_form(); /* outputs the default Wordpress search form */ ?>
</div><!--no-results-->

<?php endif;

	get_template_part('includes/post-formats/post-nav');
	wp_reset_postdata();
?>