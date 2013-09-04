<?php /* Loop Name: Single Testi */ ?>
<div class="page-header">
	<h1><?php the_title(); ?></h1>
</div>
<?php if (have_posts()) : while (have_posts()) : the_post();
	$testiname = get_post_meta($post->ID, 'my_testi_caption', true);
	$testiurl  = get_post_meta($post->ID, 'my_testi_url', true);
	$testiinfo = get_post_meta($post->ID, 'my_testi_info', true);
?>
<article id="post-<?php the_ID(); ?>" class="testimonial">
	<blockquote class="testimonial_bq">
		<?php if(has_post_thumbnail()) {
				$thumb   = get_post_thumbnail_id();
				$img_url = wp_get_attachment_url( $thumb,'full'); //get img URL
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
			<?php if($testiname) { ?>
				<span class="user"><?php echo $testiname; ?></span><?php echo ', '; ?>
			<?php } ?>
			<?php if($testiinfo) { ?>
				<span class="info"><?php echo $testiinfo; ?></span><br />
			<?php } ?>
			<?php if($testiurl) { ?>
				<a href="<?php echo $testiurl; ?>"><?php echo $testiurl; ?></a>
			<?php } ?>
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
<?php endif; ?>

<ul class="pager single-pager">
	<li class="previous">
		<?php previous_post_link('%link', theme_locals("prev_post")) ?>
		</li><!--.previous-->
	<li class="next">
		<?php next_post_link('%link', theme_locals("next_post")) ?>
	</li><!--.next-->
</ul><!--.pager-->