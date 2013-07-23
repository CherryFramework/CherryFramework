<?php /* Loop Name: Single */ ?>
<?php if (have_posts()) : while (have_posts()) : the_post();
	// The following determines what the post format is and shows the correct file accordingly
	$format = get_post_format();
	get_template_part( 'includes/post-formats/'.$format );
	if($format == '')
		get_template_part( 'includes/post-formats/standard' );
	get_template_part( 'includes/post-formats/share-buttons' );
	wp_link_pages('before=<div class="pagination">&after=</div>');
?>
<?php /* If a user fills out their bio info, it's included here */ ?>
<div class="post-author clearfix">
	<h3 class="post-author_h"><?php echo theme_locals("written_by"); ?> <?php the_author_posts_link() ?></h3>
	<p class="post-author_gravatar"><?php if(function_exists('get_avatar')) { echo get_avatar( get_the_author_meta('email'), '80' ); /* This avatar is the user's gravatar (http://gravatar.com) based on their administrative email address */  } ?></p>
	<div class="post-author_desc">
	<?php the_author_meta('description') ?> 
		<div class="post-author_link">
			<p><?php echo theme_locals("view_all"); ?>: <?php the_author_posts_link() ?></p>
		</div>
	</div>
</div><!--.post-author-->

<?php
	get_template_part( 'includes/post-formats/related-posts' );
	comments_template('', true);
	endwhile; endif; 
?>