<?php
	$motopress_cam_id = uniqid();

	// WPML filter
	$suppress_filters = get_option('suppress_filters');

	// Get Order & Orderby Parameters
	$orderby = ( of_get_option('slider_posts_orderby') ) ? of_get_option('slider_posts_orderby') : 'date';
	$order   = ( of_get_option('slider_posts_order') ) ? of_get_option('slider_posts_order') : 'DESC';

	// query
	$args = array(
		'post_type'        => 'slider',
		'posts_per_page'   => -1,
		'post_status'      => 'publish',
		'orderby'          => $orderby,
		'order'            => $order,
		'suppress_filters' => $suppress_filters
		);
	$slides = get_posts($args);
	if (empty($slides)) return;

	$slider_default_params = array(
		"alignment"      => "'topCenter'", //topLeft, topCenter, topRight, centerLeft, center, centerRight, bottomLeft, bottomCenter, bottomRight
		"barDirection"   => "'leftToRight'", //'leftToRight', 'rightToLeft', 'topToBottom', 'bottomToTop'
		"barPosition"    => "'top'", //'bottom', 'left', 'top', 'right'
		"easing"         => "'easeOutQuad'",
		"mobileEasing"   => "''",
		"mobileFx"       => "''",
		"gridDifference" => "250",
		"imagePath"      => "'images/'",
		"minHeight"      => "'147px'", //you can also leave it blank
		"height"         => "'47.4%'", //here you can type pixels (for instance '300px'), a percentage (relative to the width of the slideshow, for instance '50%') or 'auto'
		"loaderColor"    => "'#ffffff'",
		"loaderBgColor"  => "'#eb8a7c'",
		"loaderOpacity"  => "1", //0, .1, .2, .3, .4, .5, .6, .7, .8, .9, 1
		"loaderPadding"  => "0", //how many empty pixels you want to display between the loader and its background
		"loaderStroke"   => "3", //the thickness both of the pie loader and of the bar loader. Remember: for the pie, the loader thickness must be less than a half of the pie diameter
		"pieDiameter"    => "33",
		"piePosition"    => "'rightTop'",
		"portrait"       => "true"
	);
	$slider_filtered_params = apply_filters( 'cherry_slider_params', $slider_default_params );
?>

<script type="text/javascript">
//    jQuery(window).load(function() {
		jQuery(function() {
			var myCamera = jQuery('#camera<?php echo $motopress_cam_id; ?>');
			if (!myCamera.hasClass('motopress-camera')) {
				myCamera.addClass('motopress-camera');
				myCamera.camera({
					autoAdvance         : <?php echo of_get_option('sl_slideshow'); ?>, //true, false
					mobileAutoAdvance   : <?php echo of_get_option('sl_slideshow'); ?>, //true, false. Auto-advancing for mobile devices
					cols                : <?php echo of_get_option('sl_columns'); ?>,
					fx                  : "<?php echo of_get_option('sl_effect'); ?>", //'random','simpleFade', 'curtainTopLeft', 'curtainTopRight', 'curtainBottomLeft',          'curtainBottomRight', 'curtainSliceLeft', 'curtainSliceRight', 'blindCurtainTopLeft', 'blindCurtainTopRight', 'blindCurtainBottomLeft', 'blindCurtainBottomRight', 'blindCurtainSliceBottom', 'blindCurtainSliceTop', 'stampede', 'mosaic', 'mosaicReverse', 'mosaicRandom', 'mosaicSpiral', 'mosaicSpiralReverse', 'topLeftBottomRight', 'bottomRightTopLeft', 'bottomLeftTopRight', 'bottomLeftTopRight'
					loader              : "<?php echo of_get_option('sl_loader'); ?>", //pie, bar, none (even if you choose "pie", old browsers like IE8- can't display it... they will display always a loading bar)
					navigation          : <?php echo of_get_option('sl_dir_nav'); ?>, //true or false, to display or not the navigation buttons
					navigationHover     : <?php echo of_get_option('sl_dir_nav_hide'); ?>, //if true the navigation button (prev, next and play/stop buttons) will be visible on hover state only, if false they will be visible always
					pagination          : <?php echo of_get_option('sl_control_nav'); ?>,
					playPause           : <?php echo of_get_option('sl_play_pause_button'); ?>, //true or false, to display or not the play/pause buttons
					rows                : <?php echo of_get_option('sl_rows'); ?>,
					slicedCols          : <?php echo of_get_option('sl_columns'); ?>,
					slicedRows          : <?php echo of_get_option('sl_rows'); ?>,
					thumbnails          : <?php echo of_get_option('sl_thumbnails', 'false'); ?>,
					time                : <?php echo of_get_option('sl_pausetime'); ?>, //milliseconds between the end of the sliding effect and the start of the next one
					transPeriod         : <?php echo of_get_option('sl_animation_speed'); ?>, //lenght of the sliding effect in milliseconds
					hover               : <?php echo of_get_option('sl_pause_on_hover', 'true'); ?>, //pause on state hover. Not available for mobile devices
					<?php
					//filtered params output
					foreach ($slider_filtered_params as $param => $param_value) {
						echo $param . " : " . $param_value . ",\n";
					}
					?>
					////////callbacks
					onEndTransition     : function(){}, //this callback is invoked when the transition effect ends
					onLoaded            : function(){}, //this callback is invoked when the image on a slide has completely loaded
					onStartLoading      : function(){}, //this callback is invoked when the image on a slide start loading
					onStartTransition   : function(){} //this callback is invoked when the transition effect starts
				});
			}
		});
//    });
</script>

<div id="camera<?php echo $motopress_cam_id; ?>" class="camera_wrap camera">
	<?php foreach( $slides as $k => $slide ) {
			//Check if WPML is activated
			if ( defined( 'ICL_SITEPRESS_VERSION' ) ) {
				global $sitepress;

				$post_lang = $sitepress->get_language_for_element($slide->ID, 'post_slider');
				$curr_lang = $sitepress->get_current_language();
				// Unset not translated posts
				if ( $post_lang != $curr_lang ) {
					unset( $slides[$k] );
				}
				// Post ID is different in a second language Solution
				if ( function_exists( 'icl_object_id' ) ) {
					$slide = get_post( icl_object_id( $slide->ID, 'slider', true ) );
				}
			}

			$caption            = get_post_meta($slide->ID, 'my_slider_caption', true);
			$url                = get_post_meta($slide->ID, 'my_slider_url', true);
			$sl_image_url       = wp_get_attachment_image_src( get_post_thumbnail_id($slide->ID), 'slider-post-thumbnail');
			$sl_small_image_url = wp_get_attachment_image_src( get_post_thumbnail_id($slide->ID), 'slider-thumb');
			$banner_animation   = of_get_option('sl_banner');

			if ( $sl_image_url[0]=='' ) {
				$sl_image_url[0] = PARENT_URL."/images/blank.gif";
			}
			if ( $url!='' ) {
				$url = "data-link='$url'";
			}
			if ( $sl_small_image_url[0]!='' ) {
				$sl_small_image_url[0] = "data-thumb='$sl_small_image_url[0]'";
			} else {
				$sl_small_image_url[0] = "data-thumb='$sl_image_url[0]'";
			}

			echo "<div data-src='$sl_image_url[0]' $url $sl_small_image_url[0]>";
				if ($caption) { ?>
					<div class="camera_caption <?php echo $banner_animation;?>">
						<?php echo stripslashes(htmlspecialchars_decode($caption)); ?>
					</div>
				<?php }
			echo "</div>";
		}

		wp_reset_postdata();
	?>
</div>
