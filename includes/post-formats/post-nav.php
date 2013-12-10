<?php 
if ( function_exists('pagination') ) :
	pagination( $wp_query->max_num_pages );
else :
	if ( $wp_query->max_num_pages > 1 ) : ?>
	<ul class="pager">
		<li class="previous">
			<?php next_posts_link(theme_locals("older")) ?>
		</li><!--.older-->
		<li class="next">
			<?php previous_posts_link(theme_locals("newer")) ?>
		</li><!--.newer-->
	</ul><!--.oldernewer-->
	<?php endif;
endif; ?>
<!-- Posts navigation -->