<?php /* Loop Name: Archives */ ?>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
<div id="post-<?php the_ID(); ?>" <?php post_class('post-holder'); ?>>
	<div class="post-content">
		<?php the_content('<span>'.theme_locals("continue_reading").'</span>'); ?>
		<div class="clear"></div>
		<?php wp_link_pages(array('before' => '<p><strong>'.theme_locals("pages").'</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
		<div class="archive_lists">
			<div class="row-fluid">
				<div class="span4">
					<h3 class="archive_h"><?php echo theme_locals("last_posts"); ?></h3>
					<div class="list styled check-list">
						<ul>
						<?php $archive_30 = get_posts('numberposts=30');
						foreach($archive_30 as $post) : ?>
							<li><a href="<?php the_permalink(); ?>"><?php the_title();?></a></li>
						<?php endforeach; ?>
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
					</div><!-- .archive_lists -->
				</div>
			</div>
		</div><!-- .post-content -->
	</div>
</div>
<?php endwhile; endif; ?>