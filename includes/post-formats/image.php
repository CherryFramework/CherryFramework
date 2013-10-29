<article id="post-<?php the_ID(); ?>" <?php post_class('post__holder'); ?>>
<?php if(!is_singular()) : ?>
	<header class="post-header">
		<h2 class="post-title"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
	</header>
<?php endif; ?>
	<div class="post-thumb clearfix">
			<?php get_template_part('includes/post-formats/post-thumb'); ?>
		<div class="clear"></div>
	</div>
	<!-- Post Content -->
	<div class="post_content">
		<?php the_content(''); ?>
		<div class="clear"></div>
	</div>
	<!-- //Post Content -->
	<?php get_template_part('includes/post-formats/post-meta'); ?>
</article><!--//.post__holder-->