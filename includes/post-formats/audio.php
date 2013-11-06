<article id="post-<?php the_ID(); ?>" <?php post_class('post__holder'); ?>>
	<?php if(!is_singular()) : ?>
	<header class="post-header">
		<h2 class="post-title"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
	</header>
	<?php endif; ?>
	<?php
			// get audio attribute
			$audio_title  = get_post_meta(get_the_ID(), 'tz_audio_title', true);
			$audio_artist = get_post_meta(get_the_ID(), 'tz_audio_artist', true);
			$audio_format = get_post_meta(get_the_ID(), 'tz_audio_format', true);
			$audio_url    = get_post_meta(get_the_ID(), 'tz_audio_url', true);
			// get content URL
			$content_url = content_url();
			$content_str = 'wp-content';
			
			$pos = strpos($audio_url, $content_str);
			if ($pos === false) {
				$file = $audio_url;
			} else {
				$audio_new   = substr($audio_url, $pos+strlen($content_str), strlen($audio_url) - $pos);
				$file        = $content_url.$audio_new;
			}
		?>
		<div class="audio-wrap">
			<script type="text/javascript">
				jQuery(document).ready(function(){
					var myPlaylist_<?php the_ID(); ?> = new jPlayerPlaylist({
						jPlayer: "#jquery_jplayer_<?php the_ID(); ?>",
						cssSelectorAncestor: "#jp_container_<?php the_ID(); ?>"
					}, [
					{
						title:"<?php echo $audio_title; ?>",
						artist:"<?php echo $audio_artist; ?>",
						<?php echo $audio_format; ?>: "<?php echo stripslashes(htmlspecialchars_decode($file)); ?>"
					}
					], {
						playlistOptions: {
						enableRemoveControls: false
					},
					ready: function () {
						jQuery(this).jPlayer("setMedia", {
							<?php echo $audio_format; ?>: "<?php echo stripslashes(htmlspecialchars_decode($file)); ?>"
							});
						},
					swfPath: "<?php echo get_template_directory_uri(); ?>/flash",
					supplied: "mp3, all",
					wmode: "window"
					});
				});
			</script>
			<!-- BEGIN audio -->
			<div id="jquery_jplayer_<?php the_ID(); ?>" class="jp-jplayer"></div>
			<div id="jp_container_<?php the_ID(); ?>" class="jp-audio">
				<div class="jp-type-single">
					<div class="jp-gui">
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
				<div class="jp-playlist">
					<ul>
						<li></li>
					</ul>
				</div>
			</div>
			<!-- END audio -->
		</div>
	<!-- Post Content -->
	<div class="post_content">
		<?php the_content(''); ?>
		<div class="clear"></div>
	</div>
	<!--// Post Content -->
	<?php get_template_part('includes/post-formats/post-meta'); ?>
</article><!--//.post__holder-->