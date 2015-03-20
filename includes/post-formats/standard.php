<article id="post-<?php the_ID(); ?>" <?php post_class('post__holder'); ?>>
		<?php if(!is_singular()) : ?>
		<header class="post-header">
			<?php if(is_sticky()) : ?>
				<h5 class="post-label"><?php echo theme_locals("featured");?></h5>
			<?php endif; ?>
			<h2 class="post-title"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
		</header>
		<?php endif; ?>
		<?php get_template_part('includes/post-formats/post-thumb'); ?>

		<?php if ( !is_singular() ) : ?>
		<!-- Post Content -->
		<div class="post_content">
			<?php
				if (of_get_option('post_excerpt')=="true" || of_get_option('post_excerpt')=='') { ?>
					<div class="excerpt">
					<?php

					if (has_excerpt()) {
						the_excerpt();
					} else {
						if (!is_search()) {
							$content = get_the_content();
							echo apply_filters( 'cherry_standard_post_content_list', wp_trim_words( $content, 55 ) );
						} else {
							$excerpt = get_the_excerpt();
							echo apply_filters( 'cherry_standard_post_content_search', wp_trim_words( $excerpt, 55 ) );
						}
					} ?>
				</div>
			<?php }
				$button_text = of_get_option('blog_button_text') ? apply_filters( 'cherry_text_translate', of_get_option('blog_button_text'), 'blog_button_text' ) : theme_locals("read_more") ;
			?>
			<a href="<?php the_permalink() ?>" class="btn btn-primary"><?php echo $button_text; ?></a>
			<div class="clear"></div>
		</div>

		<?php else :?>
		<!-- Post Content -->
		<div class="post_content">
			<?php the_content(''); ?>
			<div class="clear"></div>
		</div>
		<!-- //Post Content -->
		<?php endif; ?>

		<?php get_template_part('includes/post-formats/post-meta'); ?>

</article>