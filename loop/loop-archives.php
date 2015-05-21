<?php /* Loop Name: Archives */ ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

<div id="post-<?php the_ID(); ?>" <?php post_class('post-holder'); ?>>
	<div class="post-content">

		<?php the_content( '<span>' . theme_locals("continue_reading") . '</span>' ); ?>

		<div class="clear"></div>

		<?php wp_link_pages( array(
				'before'         => '<p><strong>' . theme_locals("pages") . '</strong> ',
				'after'          => '</p>',
				'next_or_number' => 'number',
			)
		); ?>

		<div class="archive_lists">
			<div class="row-fluid">
				<div class="span4">
					<h3 class="archive_h"><?php echo theme_locals("last_posts"); ?></h3>
					<div class="list styled check-list">
						<ul>
							<?php
								// WPML filter
								$suppress_filters = get_option('suppress_filters');

								$archive_args = array(
									'numberposts'      => 30,
									'suppress_filters' => $suppress_filters
								);

								$archive_30 = get_posts( $archive_args );

								foreach ( $archive_30 as $key => $post ) :

									// Unset not translated posts
									if ( function_exists( 'wpml_get_language_information' ) ) {
										global $sitepress;

										$check              = wpml_get_language_information( $post->ID );
										$language_code      = substr( $check['locale'], 0, 2 );
										if ( $language_code != $sitepress->get_current_language() ) {
											unset( $posts[ $key ] );
										}

										// Post ID is different in a second language Solution
										if ( function_exists( 'icl_object_id' ) ) {
											$post = get_post( icl_object_id( $post->ID, $type, true ) );
										}
									} ?>

									<li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>

							<?php endforeach;
							wp_reset_postdata(); // restore the global $post variable ?>

						</ul>
					</div>
				</div>
				<div class="span4">
					<h3 class="archive_h"><?php echo theme_locals("archives_month"); ?></h3>
					<div class="list styled check-list">
						<ul>
							<?php wp_get_archives('type=monthly'); ?>
						</ul>
					</div>
				</div>
				<div class="span4">
					<h3 class="archive_h"><?php echo theme_locals("archives_subject"); ?></h3>
					<div class="list styled check-list">
						<ul>
							<?php wp_list_categories( 'title_li=' ); ?>
						</ul>
					</div>
				</div>
			</div>
		</div><!-- .archive_lists -->
	</div><!-- .post-content -->
</div>

<?php endwhile; endif; ?>