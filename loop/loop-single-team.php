<?php /* Loop Name: Single Team */ ?>
<?php if ( have_posts() ) : while ( have_posts() ) : the_post();
	$teampos  = get_post_meta($post->ID, 'my_team_pos', true);
	$teaminfo = get_post_meta($post->ID, 'my_team_info', true);
?>
<div id="post-<?php the_ID(); ?>" <?php post_class('post'); ?>>
	<article class="team-holder single-post">
		<div class="page-header">
			<h1><?php the_title(); ?></h1>
			<?php if ( isset($teampos) ) { ?>
				<span class="page-desc"><?php echo $teampos; ?></span>
			<?php } ?>
		</div>
		<?php if(has_post_thumbnail()) {
			$thumb   = get_post_thumbnail_id();
			$img_url = wp_get_attachment_url( $thumb,'thumbnail'); //get img URL
			$image   = aq_resize( $img_url, 120, 120, true ); //resize & crop img
		?>
		<figure class="featured-thumbnail">
			<img src="<?php echo $image ?>" alt="<?php the_title(); ?>" />
		</figure>
		<?php } ?>
		<div class="team-content post-content">
			<?php the_content(); ?>
			<div class="clear"></div>
			<?php
				if ( isset($teaminfo) ) { ?>
					<span class="page-desc"><?php echo $teaminfo; ?></span>
				<?php }
			?>
			<?php cherry_get_post_networks() ?>
		</div><!--.post-content-->
	</article>
</div><!-- #post-## -->
<?php endwhile; /* end loop */ endif; ?>