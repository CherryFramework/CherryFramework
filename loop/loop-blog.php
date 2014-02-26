<?php /* Loop Name: Blog */ ?>
<!-- displays the tag's description from the Wordpress admin -->
<?php
	if (is_tag())
		echo tag_description();

	if (have_posts()) : while (have_posts()) : the_post();
		// The following determines what the post format is and shows the correct file accordingly
		echo '<div class="post_wrapper">';
			$format = get_post_format();
			get_template_part( 'includes/post-formats/'.$format );

			if ($format == '')
				get_template_part( 'includes/post-formats/standard' );
		echo '</div>';
		endwhile; else: ?>

		<div class="no-results">
			<?php echo '<p><strong>' .theme_locals("there_has"). '</strong></p>'; ?>
			<p><?php echo theme_locals("we_apologize"); ?> <a href="<?php echo home_url(); ?>/" title="<?php bloginfo('description'); ?>"><?php echo theme_locals("return_to"); ?></a> <?php echo theme_locals("search_form"); ?></p>
				<?php get_search_form(); /* outputs the default Wordpress search form */ ?>
		</div><!--no-results-->
	<?php endif;

if ( !is_home() ) {
	get_template_part('includes/post-formats/post-nav');
} ?>