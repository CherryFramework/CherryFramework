<article id="post-<?php the_ID(); ?>" <?php post_class('post__holder'); ?>>
	<?php if(!is_singular()) : ?>
	<header class="post-header">
		<h2 class="post-title"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
	</header>
	<?php endif; ?>
	<?php 
		// get video attribute
		$video_title  = get_post_meta(get_the_ID(), 'tz_video_title', true);
		$video_artist = get_post_meta(get_the_ID(), 'tz_video_artist', true);
		$embed        = get_post_meta(get_the_ID(), 'tz_video_embed', true);
		$m4v_url      = get_post_meta(get_the_ID(), 'tz_m4v_url', true);
		$ogv_url      = get_post_meta(get_the_ID(), 'tz_ogv_url', true);

		// get content URL
		$content_url = content_url();
		$content_str = 'wp-content';
		
		$pos1     = strpos($m4v_url, $content_str);
		if ($pos1 === false) {
			$file1 = $m4v_url;
		} else {
			$m4v_new  = substr($m4v_url, $pos1+strlen($content_str), strlen($m4v_url) - $pos1);
			$file1    = $content_url.$m4v_new;
		}

		$pos2     = strpos($ogv_url, $content_str);
		if ($pos2 === false) {
			$file2 = $ogv_url;
		} else {
			$ogv_new  = substr($ogv_url, $pos2+strlen($content_str), strlen($ogv_url) - $pos2);
			$file2    = $content_url.$ogv_new;
		}
		
		$html5Class = '';
		if ($embed == '')
			$html5Class = 'html5-video';
		
		// get thumb
		if(has_post_thumbnail()) {
			$thumb   = get_post_thumbnail_id();
			$img_url = wp_get_attachment_url( $thumb,'full'); //get img URL
			$image   = aq_resize( $img_url, 770, 380, true ); //resize & crop img
		}
	?>
	<div class="video-wrap <?php echo $html5Class; ?>">
		<?php
			if ($embed != '') {
				echo stripslashes(htmlspecialchars_decode($embed));
			} else { ?>
				<script type="text/javascript">
					jQuery(document).ready(function(){
						jQuery("#jquery_jplayer_<?php the_ID(); ?>").jPlayer({
							ready: function () {
								jQuery(this).jPlayer("setMedia", {
									m4v: "<?php echo stripslashes(htmlspecialchars_decode($file1)); ?>",
									ogv: "<?php echo stripslashes(htmlspecialchars_decode($file2)); ?>" <?php if(has_post_thumbnail()) {?>,
									poster: "<?php echo $image; ?>" <?php } ?>
								});
							},
							swfPath: "<?php echo get_template_directory_uri(); ?>/flash",
							solution: "flash, html",
							supplied: "ogv, m4v, all",
							cssSelectorAncestor: "#jp_container_<?php the_ID(); ?>",
							size: {
								width: "100%",
								height: "100%"
							}
						});
					});
				</script>
				
				<!-- BEGIN video -->
				<div id="jp_container_<?php the_ID(); ?>" class="jp-video fullwidth">
					<div class="jp-type-list-parent">
						<div class="jp-type-single">
							<div id="jquery_jplayer_<?php the_ID(); ?>" class="jp-jplayer"></div>
							<div class="jp-gui">
								<div class="jp-video-play">
									<a href="javascript:;" class="jp-video-play-icon" tabindex="1" title="Play">Play</a>
								</div>
								<div class="jp-interface">
									<div class="jp-progress">
										<div class="jp-seek-bar">
											<div class="jp-play-bar"></div>
										</div>
									</div>
									<div class="jp-duration"></div>
									<div class="jp-time-sep"></div>
									<div class="jp-current-time"></div>
									<div class="jp-controls-holder">
										<ul class="jp-controls">
											<li><a href="javascript:;" class="jp-previous" tabindex="1" title="<?php echo theme_locals("prev")?>"><span><?php echo theme_locals("prev")?></span></a></li>
											<li><a href="javascript:;" class="jp-play" tabindex="1" title="<?php echo theme_locals("play")?>"><span><?php echo theme_locals("play")?></span></a></li>
											<li><a href="javascript:;" class="jp-pause" tabindex="1" title="<?php echo theme_locals("pause")?>"><span><?php echo theme_locals("pause")?></span></a></li>
											<li><a href="javascript:;" class="jp-next" tabindex="1" title="<?php echo theme_locals("next") ?>"><span><?php echo theme_locals("next")?></span></a></li>
											<li><a href="javascript:;" class="jp-stop" tabindex="1" title="<?php echo theme_locals("stop") ?>"><span><?php echo theme_locals("stop")?></span></a></li>
										</ul>
										<div class="jp-volume-bar">
											<div class="jp-volume-bar-value"></div>
										</div>
										<ul class="jp-toggles">
											<li><a href="javascript:;" class="jp-mute" tabindex="1" title="<?php echo theme_locals("mute")?>"><span><?php echo theme_locals("mute") ?></span></a></li>
											<li><a href="javascript:;" class="jp-unmute" tabindex="1" title="<?php echo theme_locals("unmute")?>"><span><?php echo theme_locals("unmute") ?></span></a></li>
										</ul>
									</div>
								</div>
								<div class="jp-no-solution">
									<?php echo theme_locals("update_required") ?>
								</div>
							</div>
						</div>
					</div>
				</div><!-- END video -->
			<?php }
		?>
	</div>
	<!-- Post Content -->
	<div class="post_content">
		<?php the_content(''); ?>
		<div class="clear"></div>
	</div>
	<!-- //Post Content -->
<?php
		get_template_part('includes/post-formats/post-meta');
?>
</article><!--//.post__holder-->