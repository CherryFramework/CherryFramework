<?php
// Set the content width based on the theme's design and stylesheet.
if ( ! isset( $content_width ) )
	$content_width = 604;

// The excerpt based on words
function my_string_limit_words($string, $word_limit){
	$words = explode(' ', $string, ($word_limit + 1));
	if( count($words) > $word_limit )
		array_pop($words);
	return implode(' ', $words).'... ';
}

// The excerpt based on character
function my_string_limit_char($excerpt, $substr=0){
	$string = strip_tags(str_replace('...', '...', $excerpt));
	if ( $substr > 0 ) {
		$string = substr($string, 0, $substr);
	}
	return $string;
}

// Generates a random string
function gener_random($length){
	srand((double)microtime()*1000000 );
	$random_id = "";
	$char_list = "abcdefghijklmnopqrstuvwxyz";
	for( $i = 0; $i < $length; $i++ ) {
		$random_id .= substr($char_list,(rand()%(strlen($char_list))), 1);
	}
	return $random_id;
}

// Remove Empty Paragraphs
add_filter('the_content', 'shortcode_empty_paragraph_fix');
function shortcode_empty_paragraph_fix($content) {
	$array = array (
			'<p>['    => '[',
			']</p>'   => ']',
			']<br />' => ']'
	);
	$content = strtr($content, $array);
	return $content;
}


/**
 * Adds the new column named 'Thumbnail'.
 */

// For posts.
add_filter( 'manage_posts_columns',       'cherry_add_thumb_column' );
add_action( 'manage_posts_custom_column', 'cherry_add_thumb_value', 10, 2 );

// For pages.
add_filter( 'manage_pages_columns',       'cherry_add_thumb_column' );
add_action( 'manage_pages_custom_column', 'cherry_add_thumb_value', 10, 2 );

function cherry_add_thumb_column( $cols ) {
	$screen     = get_current_screen();
	$post_type  = $screen->post_type;

	/**
	 * Filters the array of CPT who support thumbnail.
	 *
	 * @since 3.1.5
	 * @param array $args
	 */
	$args = apply_filters( 'cherry_add_thumb_column_support', array(
		'post', 'page', 'services', 'testi', 'portfolio', 'slider', 'team',
	) );

	if ( in_array( $post_type, $args ) ) {
		$cols['thumbnail'] = theme_locals("thumbnail");
	}

	return $cols;
}

/**
 * Add output for custom columns on the "menu items" screen.
 *
 * @param  string  $column
 * @param  int     $post_id
 * @return void
 */
function cherry_add_thumb_value( $column_name, $post_id ) {
	$width  = (int) 50;
	$height = (int) 50;

	if ( 'thumbnail' == $column_name ) :

		// Image from gallery.
		$attachments = get_children( array(
			'numberposts'    => 1,
			'order'          => 'ASC',
			'post_mime_type' => 'image',
			'post_parent'    => $post_id,
			'post_status'    => null,
			'post_type'      => 'attachment',
		) );

		$thumb = get_the_post_thumbnail( $post_id, array( $width, $height ) );

		if ( empty( $thumb ) ) {

			foreach ( $attachments as $attachment_id => $attachment ) {
				$thumb = wp_get_attachment_image( $attachment_id, array( $width, $height ), true );
			}

		}

		if ( !empty( $thumb ) ) {
			echo $thumb;
		} else {
			echo theme_locals("none");
		}

	endif;
}

/**
 * Adds the new custom columns on the 'Portfolio' screen.
 */
add_filter( 'manage_edit-portfolio_columns', 'cherry_edit_portfolio_columns' );
add_action( 'manage_portfolio_posts_custom_column' , 'cherry_portfolio_columns', 10, 2 );

function cherry_edit_portfolio_columns( $post_columns ) {
	$screen     = get_current_screen();
	$post_type  = $screen->post_type;
	$columns    = array();
	$taxonomies = array();

	// Adds the checkbox column.
	$columns['cb'] = $post_columns['cb'];

	// Add custom columns.
	$columns['title']                = theme_locals("title");
	$columns['portfolio_categories'] = theme_locals("categories");
	$columns['portfolio_tags']       = theme_locals("tags");

	// Add the comments column.
	if ( !empty( $post_columns['comments'] ) )
		$columns['comments'] = $post_columns['comments'];

	$columns['date']      = $post_columns['date'];
	$columns['thumbnail'] = $post_columns['thumbnail'];

	return $columns;
}

function cherry_portfolio_columns( $column, $post_id ) {

	switch ( $column ) {

		case 'portfolio_categories':

			$terms = get_the_term_list( $post_id , 'portfolio_category' , '' , ',' , '' );
			echo !empty( $terms ) ? $terms : theme_locals('uncategorized');
			break;

		case 'portfolio_tags':

			$terms = get_the_term_list( $post_id , 'portfolio_tag' , '' , ',' , '' );
			echo !empty( $terms ) ? $terms : '&mdash;';
			break;

		// Just break out of the switch statement for everything else.
		default :
			break;
	}
}

// Only run our customization on the 'edit.php' page in the admin.
add_action( 'load-edit.php', 'cherry_portfolio_load_edit' );
function cherry_portfolio_load_edit() {
	$screen = get_current_screen();

	if ( !empty( $screen->post_type ) && 'portfolio' === $screen->post_type ) {
		add_filter( 'request', 'cherry_portfolio_request' );
		add_action( 'restrict_manage_posts', 'cherry_show_portfolio_filter' );
	}

	add_action( 'admin_head', 'cherry_thumb_column_print_styles');
}

/**
 * Filter on the 'request' hook to change the 'order' and 'orderby' query variables when
 * viewing the "edit" screen in the admin.
 *
 * @param  array $vars
 * @return array
 */
function cherry_portfolio_request( $vars ) {

	if ( isset( $vars['orderby'] ) ) :

		if ( isset( $_GET['orderby'] ) ) {
			$vars['orderby'] = $_GET['orderby'];
		} else {
			$vars['orderby'] = 'date';
		}

	endif;

	if ( isset( $vars['order'] ) ) :

		if ( isset( $_GET['order'] ) && 'asc' == $_GET['order'] ) {
			$vars['order'] = 'asc';
		} else {
			$vars['order'] = 'desc';
		}

	endif;

	return $vars;
}

/**
 * Renders a portfolio categories and tags dropdown on the table nav.
 */
function cherry_show_portfolio_filter() {
	$category  = isset( $_GET['portfolio_category'] ) ? esc_attr( $_GET['portfolio_category'] ) : '';
	$tag       = isset( $_GET['portfolio_tag'] ) ? esc_attr( $_GET['portfolio_tag'] ) : '';
	$cat_terms = get_terms( 'portfolio_category' );
	$tag_terms = get_terms( 'portfolio_tag' );

	if ( !empty( $cat_terms ) ) {
		echo '<select name="portfolio_category" class="postform">';

		echo '<option value="" ' . selected( '', $category, false ) . '>' . theme_locals("view_all_") . ' ' . __( 'categories', CURRENT_THEME ) . '</option>';

		foreach ( $cat_terms as $term )
			printf( '<option value="%s"%s>%s (%s)</option>', esc_attr( $term->slug ), selected( $term->slug, $category, false ), esc_html( $term->name ), esc_html( $term->count ) );

		echo '</select>';
	}

	if ( !empty( $tag_terms ) ) {
		echo '<select name="portfolio_tag" class="postform">';

		echo '<option value="" ' . selected( '', $category, false ) . '>' . theme_locals("view_all_") . ' ' . __( 'tags', CURRENT_THEME ) . '</option>';

		foreach ( $tag_terms as $term )
			printf( '<option value="%s"%s>%s (%s)</option>', esc_attr( $term->slug ), selected( $term->slug, $tag, false ), esc_html( $term->name ), esc_html( $term->count ) );

		echo '</select>';
	}
}

/**
 * Style adjustments for the 'Thumbnail' column.
 */
function cherry_thumb_column_print_styles() { ?>
	<style type="text/css">
	.edit-php .wp-list-table td.thumbnail.column-thumbnail,
	.edit-php .wp-list-table th.manage-column.column-thumbnail,
	.edit-php .wp-list-table td.author_name.column-author_name,
	.edit-php .wp-list-table th.manage-column.column-author_name {
		text-align: center;
	}
	</style>
<?php }


/*-----------------------------------------------------------------------------------*/
/* Output image */
/*-----------------------------------------------------------------------------------*/
if ( !function_exists( 'tz_image' ) ) {
	function tz_image( $postid = null, $imagesize ) {

		$post_id = ( null === $postid ) ? get_the_ID() : $postid;

		if ( has_post_thumbnail( $postid ) ):

			$lightbox = get_post_meta( $post_id, 'tz_image_lightbox', TRUE );

			if ( $lightbox == 'yes' )
				$lightbox = TRUE;
			else
				$lightbox = FALSE;

			$src     = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), array( '9999','9999' ), false, '' );
			$thumb   = get_post_thumbnail_id();
			$img_url = wp_get_attachment_url( $thumb, 'full' ); //get img URL
			$image   = aq_resize( $img_url, 700, 460, true ); //resize & crop img

			if ( $lightbox ) :
				echo '<figure class="featured-thumbnail thumbnail large"><a class="image-wrap" rel="prettyPhoto" title="'. get_the_title() .'" href="'. $src[0] .'"><img src="'. $image .'" alt="'. get_the_title() .'" /><span class="zoom-icon"></span></a></figure><div class="clear"></div>';
			else :
				echo '<figure class="featured-thumbnail thumbnail large"><img src="'. $image .'" alt="'. get_the_title() .'" /></figure><div class="clear"></div>';
			endif;

		endif;

	}
}


/*-----------------------------------------------------------------------------------*/
/* Output gallery */
/*-----------------------------------------------------------------------------------*/
if ( !function_exists( 'tz_grid_gallery' ) ) {

	function tz_grid_gallery($postid, $imagesize) {
		$single_folio_layout = of_get_option('single_folio_layout','grid');
		$single_gallery_layout = of_get_option('single_gallery_layout', 'grid');

		if ( $single_gallery_layout == 'masonry' ) {

			add_action( 'wp_footer', 'cherry_enqueue_isotope' );

		} ?>

		<script type="text/javascript">
			jQuery(document).ready(function () {
				var
						masonrycontainer = jQuery('.grid_gallery_inner')
					,	col = 3
					,	layout = "<?php echo $single_gallery_layout ?>"
					;
				if( layout =='masonry'){
					masonrycontainer.isotope({
						itemSelector : '.gallery_item'
					,	masonry: { columnWidth: Math.floor(masonrycontainer.width() / col) }
					});

					jQuery(window).resize(function(){
						jQuery('.gallery_item', masonrycontainer).width(Math.floor(masonrycontainer.width() / col));
						masonrycontainer.isotope({
							masonry: { columnWidth: Math.floor(masonrycontainer.width() / col) }
						});
					}).trigger('resize');
				}
			});
		</script>
		<div class="grid_gallery clearfix">
			<div class="grid_gallery_inner">
			<?php

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

				$lightbox = get_post_meta(get_the_ID(), 'tz_image_lightbox', TRUE);
				if($lightbox == 'yes')
					$lightbox = TRUE;
				else
					$lightbox = FALSE;
				$src = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), array( '9999','9999' ), false, '' );

			if ($attachments) :
				foreach ($attachments as $attachment) :
					$attachment_url = wp_get_attachment_image_src( $attachment->ID, 'full' );
					$url			= $attachment_url['0'];
					$imgWidth		= $attachment_url['1'];
					$imgHeight		= $attachment_url['2'];

					switch ($single_gallery_layout) {
						case 'grid':
							if($single_folio_layout=='grid'){
								$new_width	= 260;
								$new_height	= 160;
							}else{
								$new_width	= 390;
								$new_height	= 260;
							}
							break;
						case 'masonry':
							if($single_folio_layout=='grid'){
								$new_width	= 260;
								$new_height	= $imgHeight / $imgWidth * $new_width;
							}else{
								$new_width	= 390;
								$new_height	= $imgHeight / $imgWidth * $new_width;
							}
							break;
					}
					$image	= aq_resize($url, $new_width, $new_height, true);
				?>
				<figure class="gallery_item featured-thumbnail thumbnail single-gallery-item">
					<?php if($lightbox) : ?>
					<a href="<?php echo $attachment_url['0'] ?>" class="image-wrap" rel="prettyPhoto[gallery]">
						<img alt="<?php echo apply_filters('the_title', $attachment->post_title); ?>" src="<?php echo $image ?>" width="<?php echo $new_width ?>" height="<?php echo $new_height ?>" />
						<span class="zoom-icon"></span>
						</a>
					<?php else : ?>
						<img alt="<?php echo apply_filters('the_title', $attachment->post_title); ?>" src="<?php echo $image ?>" width="<?php echo $new_width ?>" height="<?php echo $new_height ?>" />
					<?php endif; ?>
				</figure>
			<?php endforeach;?>

			<?php endif; ?>

			</div>
		<!--END .slider -->
		</div>
	<?php }
}

function cherry_enqueue_isotope() {

	if ( !wp_script_is( 'isotope', 'enqueued' ) ) {

		wp_enqueue_script( 'isotope', PARENT_URL . '/js/jquery.isotope.js', array( 'jquery' ), '1.5.25', true );

	}
}

/*-----------------------------------------------------------------------------------*/
/* Output gallery slideshow */
/*-----------------------------------------------------------------------------------*/
if ( !function_exists( 'tz_gallery' ) ) {
	function tz_gallery($postid, $imagesize) { ?>
		<?php $random = gener_random(10); ?>
			<script type="text/javascript">
				jQuery(window).load(function() {
					jQuery('#flexslider_<?php echo $random ?>').flexslider({
						animation: "slide",
						animationLoop: false,
						smoothHeight : true
						<?php if ( is_rtl() ) { ?>
							,rtl : true
						<?php } ?>
					});
				});
			</script>

			<div id="flexslider_<?php echo $random ?>" class="flexslider thumbnail">
				<ul class="slides">
				<?php
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

					$lightbox = get_post_meta(get_the_ID(), 'tz_image_lightbox', TRUE);
					if($lightbox == 'yes')
						$lightbox = TRUE;
					else
						$lightbox = FALSE;
					$src = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), array( '9999','9999' ), false, '' );

					if ($attachments) :
						foreach ($attachments as $attachment) :
						$attachment_url = wp_get_attachment_image_src( $attachment->ID, 'full' );
						$url            = $attachment_url['0'];
						$image          = aq_resize($url, 650, 400, true);
					?>

					<li>
						<?php if($lightbox) : ?>
							<a href="<?php echo $attachment_url['0'] ?>" class="image-wrap" rel="prettyPhoto[gallery]">
								<img src="<?php echo $image; ?>" alt="<?php echo apply_filters('the_title', $attachment->post_title); ?>"/>
								<!-- <span class="zoom-icon"></span> -->
							</a>
						<?php else : ?>
							<img src="<?php echo $image; ?>" alt="<?php echo apply_filters('the_title', $attachment->post_title); ?>"/>
						<?php endif; ?>
					</li>
					<?php endforeach; ?>
					<?php endif; ?>
				</ul>
			</div>
	<?php }
}

/*-----------------------------------------------------------------------------------*/
/*	Output Audio
/*-----------------------------------------------------------------------------------*/
if ( !function_exists( 'tz_audio' ) ) {
	function tz_audio($postid) {
		// get audio attribute
		$audio_title = get_post_meta($postid, 'tz_audio_title', true);
		$audio_artist = get_post_meta($postid, 'tz_audio_artist', true);
		$audio_format = get_post_meta($postid, 'tz_audio_format', true);
		$audio_url = get_post_meta($postid, 'tz_audio_url', true);

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
		<script type="text/javascript">
			jQuery(document).ready(function(){
				var myPlaylist_<?php the_ID(); ?> = new jPlayerPlaylist({
					jPlayer: "#jquery_jplayer_<?php the_ID(); ?>",
					cssSelectorAncestor: "#jp_container_<?php the_ID(); ?>"
				}, [
				{
					title:"<?php echo $audio_title; ?>",
					artist:"<?php echo $audio_artist; ?>",
					<?php echo $audio_format; ?>: "<?php echo stripslashes(htmlspecialchars_decode($file)); ?>" <?php if(has_post_thumbnail()) {?>,
					poster: "<?php if (!isset($image)) $image = ''; echo $image; ?>" <?php } ?>
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
					wmode: "window",
					supplied: "mp3, all"
				});
			});
		</script>

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
		<?php
	}
}

/*-----------------------------------------------------------------------------------*/
/*	Output Video
/*-----------------------------------------------------------------------------------*/
if ( !function_exists( 'tz_video' ) ) {
	function tz_video($postid) {
		// get video attribute
		$video_title  = get_post_meta($postid, 'tz_video_title', true);
		$video_artist = get_post_meta($postid, 'tz_video_artist', true);
		$embed        = get_post_meta(get_the_ID(), 'tz_video_embed', true);
		$m4v_url      = get_post_meta($postid, 'tz_m4v_url', true);
		$ogv_url      = get_post_meta($postid, 'tz_ogv_url', true);

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

		// get thumb (poster image)
		$thumb        = get_post_thumbnail_id( $postid );
		$img_url      = wp_get_attachment_url( $thumb,'full'); //get img URL
		$image        = aq_resize( $img_url, 770, 380, true ); //resize & crop img

		if ($embed != '') { ?>
			<div class="video-wrap">
				<?php echo stripslashes(htmlspecialchars_decode($embed)); ?>
			</div>
		<?php } else { ?>
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
			</div>
		<?php }
	}
}

/*-----------------------------------------------------------------------------------*/
/*	Pagination
/*-----------------------------------------------------------------------------------*/
if ( !function_exists( 'pagination' ) ) {
	function pagination( $pages = '', $range = 1 ) {
		$showitems = ($range * 2) + 1;

		global $wp_query;
		$paged = (int) $wp_query->query_vars['paged'];
		if( empty($paged) || $paged == 0 ) $paged = 1;

		if ( $pages == '' ) {
			$pages = $wp_query->max_num_pages;
			if( !$pages ) {
				$pages = 1;
			}
		}
		if ( 1 != $pages ) {
			echo "<div class=\"pagination pagination__posts\"><ul>";
			if ( $paged > 2 && $paged > $range+1 && $showitems < $pages ) echo "<li class='first'><a href='".get_pagenum_link(1)."'>".theme_locals("first")."</a></li>";
			if ( $paged > 1 && $showitems < $pages ) echo "<li class='prev'><a href='".get_pagenum_link($paged - 1)."'>".theme_locals("prev")."</a></li>";

			for ( $i = 1; $i <= $pages; $i++ ) {
				if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems )) {
					echo ($paged == $i)? "<li class=\"active\"><a href='#'>".$i."</a></li>":"<li><a href='".get_pagenum_link($i)."' class=\"inactive\">".$i."</a></li>";
				}
			}

			if ( $paged < $pages && $showitems < $pages ) echo "<li class='next'><a href=\"".get_pagenum_link($paged + 1)."\">".theme_locals("next")."</a></li>";
			if ( $paged < $pages-1 && $paged+$range-1 < $pages && $showitems < $pages ) echo "<li class='last'><a href='".get_pagenum_link($pages)."'>".theme_locals("last")."</a></li>";
			echo "</ul></div>\n";
		}
	}
}


/*-----------------------------------------------------------------------------------*/
/* Custom Comments Structure
/*-----------------------------------------------------------------------------------*/
if ( !function_exists( 'mytheme_comment' ) ) {
	function mytheme_comment($comment, $args, $depth) {
		$GLOBALS['comment'] = $comment;
	?>
	<li <?php comment_class('clearfix'); ?> id="li-comment-<?php comment_ID() ?>">
		<div id="comment-<?php comment_ID(); ?>" class="comment-body clearfix">
			<div class="wrapper">
				<div class="comment-author vcard">
					<?php echo get_avatar( $comment->comment_author_email, 65 ); ?>
					<?php printf('<span class="author">%1$s</span>', get_comment_author_link()) ?>
				</div>
				<?php if ($comment->comment_approved == '0') : ?>
					<em><?php echo theme_locals("your_comment") ?></em>
				<?php endif; ?>
				<div class="extra-wrap">
					<?php comment_text() ?>
				</div>
			</div>
			<div class="wrapper">
				<div class="reply">
					<?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
				</div>
				<div class="comment-meta commentmetadata"><?php printf('%1$s', get_comment_date()) ?></div>
			</div>
		</div>
<?php }
}

/*-----------------------------------------------------------------------------------*/
/* Breadcrumbs
/*-----------------------------------------------------------------------------------*/
if ( !function_exists( 'breadcrumbs' ) ) {
	function breadcrumbs() {

	$showOnHome  = 1; // 1 - show "breadcrumbs" on home page, 0 - hide
	$delimiter   = '<li class="divider"></li>'; // divider
	$home        = get_the_title( get_option('page_on_front', true) ); // text for link "Home"
	$showCurrent = 1; // 1 - show title current post/page, 0 - hide
	$before      = '<li class="active">'; // open tag for active breadcrumb
	$after       = '</li>'; // close tag for active breadcrumb

	global $post;
	$homeLink = home_url();

	if (is_front_page()) {
		if ($showOnHome == 1)
			echo '<ul class="breadcrumb breadcrumb__t"><li><a href="' . $homeLink . '">' . $home . '</a><li></ul>';
		} else {
			echo '<ul class="breadcrumb breadcrumb__t"><li><a href="' . $homeLink . '">' . $home . '</a></li>' . $delimiter;

			if ( is_home() ) {
				$blog_text = of_get_option('blog_text');
				if ($blog_text == '' || empty($blog_text)) {
					echo get_post_field( 'post_title', get_queried_object_id() );
				}
				echo $before . $blog_text . $after;
			}
			elseif ( is_category() ) {
				$thisCat = get_category(get_query_var('cat'), false);
				if ($thisCat->parent != 0) echo get_category_parents($thisCat->parent, TRUE, ' ' . $delimiter . ' ');
				echo $before . theme_locals("category_archives").': "' . single_cat_title('', false) . '"' . $after;
			}
			elseif ( is_search() ) {
				echo $before . theme_locals("fearch_for") . ': "' . get_search_query() . '"' . $after;
			}
			elseif ( is_day() ) {
				echo '<li><a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a></li> ' . $delimiter . ' ';
				echo '<li><a href="' . get_month_link(get_the_time('Y'),get_the_time('m')) . '">' . get_the_time('F') . '</a></li> ' . $delimiter . ' ';
				echo $before . get_the_time('d') . $after;
			}
			elseif ( is_month() ) {
				echo '<li><a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a></li> ' . $delimiter . ' ';
				echo $before . get_the_time('F') . $after;
			}
			elseif ( is_year() ) {
				echo $before . get_the_time('Y') . $after;
			}
			elseif ( is_tax(get_post_type().'_category') ) {
				$post_name = get_post_type();
				echo $before . ucfirst($post_name) . ' ' . theme_locals('category_archives') . ': ' . single_cat_title( '', false ) . $after;
			}
			elseif ( is_tax(get_post_type().'_tag') ) {
				$post_name = get_post_type();
				echo $before . ucfirst($post_name) . ' ' . theme_locals('tag_archives') . ': ' . single_tag_title( '', false ) . $after;
			}
			elseif ( is_tax() ) {
				echo $before . single_term_title( '', false ) . $after;
			}
			elseif ( is_single() && !is_attachment() ) {
				if ( get_post_type() != 'post' ) {
					$post_id = get_the_ID();
					$post_name = get_post_type();
					$post_type = get_post_type_object(get_post_type());
					// echo '<li><a href="' . $homeLink . '/' . $post_type->labels->name . '/">' . $post_type->labels->name . '</a></li>';

					$terms = get_the_terms( $post_id, $post_name.'_category');
					if ( $terms && ! is_wp_error( $terms ) ) {
						echo '<li><a href="' .get_term_link(current($terms)->slug, $post_name.'_category') .'">'.current($terms)->name.'</a></li>';
						echo ' ' . $delimiter . ' ';
					} else {
						// echo '<li><a href="' . $homeLink . '/' . $post_type->labels->name . '/">' . $post_type->labels->name . '</a></li>';
					}

					if ($showCurrent == 1)
						echo $before . get_the_title() . $after;
				} else {
					$cat = get_the_category();
					if (!empty($cat)) {
						$cat  = $cat[0];
						$cats = get_category_parents($cat, TRUE, '</li>' . $delimiter . '<li>');
						if ($showCurrent == 0)
							$cats = preg_replace("#^(.+)\s$delimiter\s$#", "$1", $cats);
						echo '<li>' . substr($cats, 0, strlen($cats)-4);
					}
					if ($showCurrent == 1)
						echo $before . get_the_title() . $after;
				}
			}
			elseif ( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() ) {
				$post_type = get_post_type_object(get_post_type());
				if ( isset($post_type) ) {
					echo $before . $post_type->labels->singular_name . $after;
				}
			}
			elseif ( is_attachment() ) {
				$parent = get_post($post->post_parent);
				$cat    = get_the_category($parent->ID);
				if ( isset($cat) && !empty($cat)) {
					$cat    = $cat[0];
					echo get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
					echo '<li><a href="' . get_permalink($parent) . '">' . $parent->post_title . '</a></li>';
				}
				if ($showCurrent == 1)
					echo $before . get_the_title() . $after;
			}
			elseif ( is_page() && !$post->post_parent ) {
				if ($showCurrent == 1)
					echo $before . get_the_title() . $after;
			}
			elseif ( is_page() && $post->post_parent ) {
				$parent_id  = $post->post_parent;
				$breadcrumbs = array();
				while ($parent_id) {
					$page          = get_page($parent_id);
					$breadcrumbs[] = '<li><a href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a></li>';
					$parent_id     = $page->post_parent;
				}
				$breadcrumbs = array_reverse($breadcrumbs);
				for ($i = 0; $i < count($breadcrumbs); $i++) {
					echo $breadcrumbs[$i];
					if ($i != count($breadcrumbs)-1) echo ' ' . $delimiter . ' ';
				}
				if ($showCurrent == 1)
					echo ' ' . $delimiter . ' ' . $before . get_the_title() . $after;
			}
			elseif ( is_tag() ) {
				echo $before . theme_locals("tag_archives") . ': "' . single_tag_title('', false) . '"' . $after;
			}
			elseif ( is_author() ) {
				global $author;
				$userdata = get_userdata($author);
				echo $before . theme_locals("by") . ' ' . $userdata->display_name . $after;
			}
			elseif ( is_404() ) {
				echo $before . '404' . $after;
			}
			echo '</ul>';
		}
	} // end breadcrumbs()
}

/**
 * Outputs the category/tax/page description.
 *
 * @since  3.1.5
 * @return string
 */
add_action( 'cherry_get_title_desc', 'cherry_get_title_description' );
function cherry_get_title_description() {
	$desc = '';

	if ( is_category() )
		$desc = get_term_field( 'description', get_queried_object_id(), 'category', 'raw' );

	elseif ( is_tax() && !( is_tax( array( 'product_cat', 'product_tag' ) ) && get_query_var( 'paged' ) == 0 ) )
		$desc = get_term_field( 'description', get_queried_object_id(), get_query_var( 'taxonomy' ), 'raw' );

	else {
		$pagedesc = get_post_custom_values( 'title-desc' );

		if ( $pagedesc != '' )
			$desc = $pagedesc[0];
	}

	/**
	 * Filters description for category/tax/page.
	 *
	 * @since  3.1.5
	 */
	$desc = apply_filters( 'cherry_title_description', $desc );

	printf( '<span class="title-desc">%s</span>', wp_strip_all_tags( $desc ) );

}

/**
 * Retrieve the search results title.
 *
 * @since  3.1.5
 * @param  string  $prefix
 * @param  bool    $display
 * @return string
 */
function cherry_search_title( $prefix = '', $display = true ) {

	$title = sprintf( '%1$s: "%2$s"', $prefix, get_search_query() );

	if ( false === $display )
		return $title;

	echo $title;
}

/**
 * Retrieve the day archive title.
 *
 * @since  3.1.5
 * @param  string  $prefix
 * @param  bool    $display
 * @return string
 */
function cherry_single_day_title( $prefix = '', $display = true ) {

	$title = $prefix . ' ' . get_the_date();

	if ( false === $display )
		return $title;

	echo $title;
}

/**
 * Retrieve the year archive title.
 *
 * @since  3.1.5
 * @param  string  $prefix
 * @param  bool    $display
 * @return string
 */
function cherry_single_year_title( $prefix = '', $display = true ) {

	$title = $prefix . ' ' . get_the_date( 'Y' );

	if ( false === $display )
		return $title;

	echo $title;
}

/**
 * Retrieve the general archive title.
 *
 * @since  3.1.5
 * @param  string  $prefix
 * @param  bool    $display
 * @return string
 */
function cherry_single_archive_title( $prefix = '', $display = true ) {

	$title = $prefix;

	if ( false === $display )
		return $title;

	echo $title;
}
?>