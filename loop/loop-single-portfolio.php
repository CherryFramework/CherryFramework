<?php /* Loop Name: Single Portfolio */ ?>
<?php if (have_posts()) : while (have_posts()) : the_post();
	$fullwidth = of_get_option('single_folio_layout') != 'fullwidth';
	$left_block = $fullwidth ? cherry_get_layout_class( 'left_block' ) : cherry_get_layout_class( 'full_width_content' ) ;
	$right_block = $fullwidth ? cherry_get_layout_class( 'right_block' ) : cherry_get_layout_class( 'full_width_content' ) ;
	$meta_blok_class = $fullwidth ? '' : 'span4 float-right';
	$content_blok_class = $fullwidth ? '' : 'span8';
	$wrapper_blok_class = $fullwidth ? '' : 'row';
?>
	<!--BEGIN .hentry -->
	<div <?php post_class(); ?> id="post-<?php the_ID(); ?>">
		<div class="row">
			<div class="<?php echo $left_block; ?>">
			<?php
				// get the media elements
				$mediaType = get_post_meta($post->ID, 'tz_portfolio_type', true);
				switch ($mediaType) {
					case "Image":
						tz_image($post->ID, 'portfolio-main');
						break;

					case "Slideshow":
						tz_gallery($post->ID, 'portfolio-main');
						break;

					case "Grid Gallery":
						tz_grid_gallery($post->ID, 'portfolio-main');
						break;

					case "Video":
						tz_video($post->ID);
						break;

					case "Audio":
						tz_audio($post->ID);
						break;

					default:
						break;
				}?>
				<!--BEGIN .pager .single-pager -->
				<ul class="pager single-pager">
				<?php if (get_previous_post()) : ?>
					<li class="previous"><?php previous_post_link('%link', theme_locals("prev_post")) ?></li>
				<?php endif; ?>

				<?php if (get_next_post()) : ?>
					<li class="next"><?php next_post_link('%link', theme_locals("next_post")) ?></li>
				<?php endif; ?>
				<!--END .pager .single-pager -->
				</ul>
			</div>

			<!-- BEGIN .entry-content -->
			<div class="entry-content <?php echo $right_block; ?>">
				<!-- BEGIN .entry-meta -->
				<div class="<?php echo $wrapper_blok_class; ?>">
					<div class="entry-meta <?php echo $meta_blok_class; ?>">
						<?php
							// get the meta information and display if supplied
							$portfolioClient = get_post_meta($post->ID, 'tz_portfolio_client', true);
							$portfolioDate   = get_post_meta($post->ID, 'tz_portfolio_date', true);
							$portfolioInfo   = get_post_meta($post->ID, 'tz_portfolio_info', true);
							$portfolioURL    = get_post_meta($post->ID, 'tz_portfolio_url', true);
							$portfolioMeta   = of_get_option('folio_meta');

							if($portfolioMeta == "yes"){
								$post_type = get_post_type($post);
								if (has_term('', $post_type.'_category', $post->ID) || has_term('', $post_type.'_tag', $post->ID)) {
									echo '<div class="portfolio-meta">';
										if(has_term('', $post_type.'_category', $post->ID)){
											echo '<span class="post_category"><i class="icon-bookmark"></i>';
											echo the_terms($post->ID, $post_type.'_category','',', ');
											echo '</span>';
										}
										if(has_term('', $post_type.'_tag', $post->ID)){
											echo '<span class="post_tag"><i class="icon-tag"></i>';
											echo the_terms($post->ID, $post_type.'_tag','',', ');
											echo '</span>';
										}
									echo '</div>';
								}
							}
							if (!empty($portfolioClient) || !empty($portfolioDate) || !empty($portfolioInfo) || !empty($portfolioURL)) {
								echo '<ul class="portfolio-meta-list">';
							}

							if (!empty($portfolioClient)) {
								echo '<li>';
								echo '<strong class="portfolio-meta-key">' .theme_locals("client").":". '</strong>';
								echo '<span>' . $portfolioClient . '</span><br />';
								echo '</li>';
							}

							if (!empty($portfolioDate)) {
								echo '<li>';
								echo '<strong class="portfolio-meta-key">' . theme_locals("date").":". '</strong>';
								echo '<span>' . $portfolioDate . '</span><br />';
								echo '</li>';
							}

							if (!empty($portfolioInfo)) {
								echo '<li>';
								echo '<strong class="portfolio-meta-key">' . theme_locals("info").":". '</strong>';
								echo '<span>' . $portfolioInfo . '</span><br />';
								echo '</li>';
							}

							if (!empty($portfolioURL)) {
								echo '<li>';
								echo "<a target='_blank' href='$portfolioURL'>" .theme_locals("launch_project") . "</a>";
								echo '</li>';
							}

							if (!empty($portfolioClient) || !empty($portfolioDate) || !empty($portfolioInfo) || !empty($portfolioURL)) {
								echo '</ul>';
						}?>
					</div><!-- END .entry-meta -->
					<div class="<?php echo $content_blok_class; ?>">
					<?php
						the_content();
						//get_template_part('includes/post-formats/share-buttons');
					?>
					</div>
				</div>
			</div><!-- END .entry-content -->
		</div><!-- .row -->
		<div class="row">
			<div class="<?php echo $left_block; ?>">
				<?php
					get_template_part( 'includes/post-formats/related-posts' );
					comments_template('', true);
				?>
			</div>
		</div>
	</div>
<?php endwhile; endif; ?>