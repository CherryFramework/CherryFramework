<article id="post-<?php the_ID(); ?>" <?php post_class('post__holder'); ?>>
	<?php if(!is_singular()) { ?>
	<header class="post-header">
		<h2 class="post-title"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
	</header>
	<?php
		}
		$args = array(
			'orderby'        => 'menu_order',
			'order'          => 'ASC',
			'post_type'      => 'attachment',
			'post_parent'    => get_the_ID(),
			'post_mime_type' => 'image',
			'post_status'    => null,
			'numberposts'    => -1,
		);
		$attachments = get_posts($args);

		if(is_singular() || of_get_option('blog_sidebar_pos')!='masonry') { ?>
		<?php $random = gener_random(10); ?>
		<script type="text/javascript">
			jQuery(window).load(function() {
				jQuery('#flexslider_<?php echo $random ?>').flexslider({
					animation: "slide",
					smoothHeight: true
					<?php if ( is_rtl() ) { ?>
						,rtl : true
					<?php } ?>
				});
			});
		</script>
			<!-- Gallery Post -->
			<div class="gallery-post">
				<!-- Slider -->
				<div id="flexslider_<?php echo $random ?>" class="flexslider thumbnail">
					<ul class="slides">
						<?php
							if ($attachments) {
								foreach ($attachments as $attachment) {
									$attachment_url = wp_get_attachment_image_src( $attachment->ID, 'full' );
									$url            = $attachment_url['0'];
									$image          = aq_resize($url, 800, 400, true);
							?>
							<li><img src="<?php echo $image; ?>" alt="<?php echo apply_filters('the_title', $attachment->post_title); ?>"/></li>
							<?php
								};
							};
						?>
					</ul>
				</div>
				<!-- /Slider -->
			</div>
			<!-- /Gallery Post -->
			<!-- Post Content -->
			<div class="post_content">
				<?php the_content(''); ?>
				<div class="clear"></div>
			</div>
			<!-- //Post Content -->
			<?php get_template_part('includes/post-formats/post-meta');
		}else{
			get_template_part('includes/post-formats/post-thumb');
		}; ?>
</article><!--//.post__holder-->