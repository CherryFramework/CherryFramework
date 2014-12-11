<?php // Isotope Portfolio Init ?>

<?php // Theme Options vars
	$layout_mode = of_get_option('layout_mode');
?>

<script>
	jQuery(document).ready(function($) {
		var $container = $('#portfolio-grid'),
			items_count = $(".portfolio_item").size();

		$(window).load(function(){
			var selector = window.location.hash.replace( /^#category/, '.term' );

			if(selector == "#"){
				selector = '';
			}

			setColumnWidth();
			$container.isotope({
				itemSelector : '.portfolio_item',
				hiddenClass : 'portfolio_hidden',
				resizable : false,
				transformsEnabled : true,
				layoutMode: '<?php echo $layout_mode; ?>',
				filter: selector
			})

			$('#filters .active').removeClass('active')
			$('#filters li a[data-filter="'+selector+'"]').parent('li').addClass('active');
			change_hash(selector);

			$(window).on("debouncedresize", function( event ) {
				arrange();
			});
		});

		function getNumColumns(){
			var $folioWrapper = $('#portfolio-grid').data('cols');

			if($folioWrapper == '2cols') {
				var winWidth = $("#portfolio-grid").width(),
					column = 2;
				if (winWidth<380) column = 1;
				return column;
			}

			else if ($folioWrapper == '3cols') {
				var winWidth = $("#portfolio-grid").width(),
					column = 3;
				if (winWidth<380) column = 1;
				else if(winWidth>=380 && winWidth<788) column = 2;
				else if(winWidth>=788 && winWidth<1160) column = 3;
				else if(winWidth>=1160) column = 3;
				return column;
			}

			else if ($folioWrapper == '4cols') {
				var winWidth = $("#portfolio-grid").width(),
					column = 4;
				if (winWidth<380) column = 1;
				else if(winWidth>=380 && winWidth<788) column = 2;
				else if(winWidth>=788 && winWidth<1160) column = 3;
				else if(winWidth>=1160) column = 4;
				return column;
			}
		}

		function setColumnWidth(){
			var columns = getNumColumns(),
				containerWidth = $("#portfolio-grid").width(),
				postWidth = containerWidth/columns;
			postWidth = Math.floor(postWidth);

			$(".portfolio_item").each(function(index){
				$(this).css({"width":postWidth+"px"});
			});
		}

		function arrange(){
			setColumnWidth();
			$container.isotope('reLayout');
		}

		// Filter projects
		$('.filter a').click(function(){
			var $this = $(this).parent('li');
			// don't proceed if already active
			if ( $this.hasClass('active') ) {
				return;
			}


			var $optionSet = $this.parents('.filter');
			// change active class
			$optionSet.find('.active').removeClass('active');
			$this.addClass('active');

			var selector = $(this).attr('data-filter');
			$container.isotope({ filter: selector });
			change_hash(selector)

			var hiddenItems = 0,
				showenItems = 0;
			$(".portfolio_item").each(function(){
				if ( $(this).hasClass('portfolio_hidden') ) {
					hiddenItems++;
				};
			});

			showenItems = items_count - hiddenItems;
			if ( ($(this).attr('data-count')) > showenItems ) {
				$(".pagination__posts").css({"display" : "block"});
			} else {
				$(".pagination__posts").css({"display" : "none"});
			}
			return false;
		});
		function change_hash(hash){
			hash = hash.replace( /^.term/, 'category' );
			window.location.href = '#'+hash;

			$('.pagination a').each(function(){
				var item = $(this),
					href = item.attr('href'),
					end_slice = href.indexOf('#')==-1 ? href.length : href.indexOf('#') ;

				href = href.slice(0, end_slice);
				item.attr({'href':href+'#'+hash})
			})
		}
	});
</script>

<?php
	$i = 1;
	if ( have_posts() ) while ( have_posts() ) : the_post();

	// post ID is different in a second language solution
	if ( function_exists( 'icl_object_id' ) ) $post = get_post( icl_object_id( $post->ID, 'portfolio', true ) );

	// Get categories
	$portfolio_cats = wp_get_object_terms($post->ID, 'portfolio_category');

	// Get tags
	$portfolio_tags = !is_wp_error( wp_get_object_terms($post->ID, 'portfolio_tag')) ? wp_get_object_terms($post->ID, 'portfolio_tag') : array();

	// Theme Options vars
	$folio_filter        = of_get_option('folio_filter');
	$folio_title         = of_get_option('folio_title');
	$folio_btn           = of_get_option('folio_btn');
	$folio_excerpt       = of_get_option('folio_excerpt');
	$folio_excerpt_count = of_get_option('folio_excerpt_count');
	$lightbox            = (of_get_option('folio_lightbox') != '') ? of_get_option('folio_lightbox') : 'yes';

	// Set size for image
	$image_size = array(
		'width'  => 600,
		'height' => 380
		);

	// Get img URL, resize & crop
	$thumb   = get_post_thumbnail_id();
	$img_url = wp_get_attachment_url( $thumb,'full');
	$image   = aq_resize( $img_url, $image_size['width'], $image_size['height'], true );

	//mediaType init
	$mediaType = get_post_meta($post->ID, 'tz_portfolio_type', true);
?>
	<li class="portfolio_item <?php foreach( $portfolio_cats as $portfolio_cat ) { echo ' term_id_' . $portfolio_cat->term_id; } ?> <?php foreach( $portfolio_tags as $portfolio_tag ) { echo ' term_id_' . $portfolio_tag->term_id; } ?>">
		<div class="portfolio_item_holder">
		<?php
			if ($lightbox == "yes") :
				if ($mediaType == 'Image')
					$prettyType = 'prettyPhoto';
				else
					$prettyType = "prettyPhoto[gallery".$i."]";
				$link_href  = $img_url;
				$link_title = get_the_title($post->ID);
				$link_rel   = 'rel="'.$prettyType.'"';
				$zoom_icon  = '<span class="zoom-icon"></span>';
			else :
				$link_href  = get_permalink($post->ID);
				$link_title = theme_locals("permanent_link_to").' '.get_the_title($post->ID);
				$link_rel   = '';
				unset($zoom_icon);
			endif;

			// in any for Video and Audio posts no lightbox
			if ( ($mediaType == 'Video') || ($mediaType == 'Audio') ) {
				$link_href  = get_permalink($post->ID);
				$link_title = theme_locals("permanent_link_to").' '.get_the_title($post->ID);
				$link_rel   = '';
				unset($zoom_icon);
			} ?>

			<?php if (has_post_thumbnail()) { ?>
			<figure class="thumbnail thumbnail__portfolio">
				<a href="<?php echo $link_href; ?>" class="image-wrap" title="<?php echo $link_title; ?>" <?php echo $link_rel; ?>>
					<img src="<?php echo $image ?>" alt="<?php the_title(); ?>" />
					<?php if (isset($zoom_icon)) echo $zoom_icon; ?>
				</a>
			</figure><!--/.thumbnail__portfolio-->
			<?php }

			if ( ($mediaType == 'Slideshow') || ($mediaType == 'Grid Gallery') ) {
				// get attachments
				$thumbid = 0;
				$thumbid = get_post_thumbnail_id($post->ID);
				$images = get_children( array(
					'orderby'        => 'menu_order',
					'order'          => 'ASC',
					'post_type'      => 'attachment',
					'post_parent'    => $post->ID,
					'post_mime_type' => 'image',
					'post_status'    => null,
					'numberposts'    => -1
				) );
				// output attachments
				if ( $images ) {
					$attachment_counter = 0;
					foreach ( $images as $attachment_id => $attachment ) {
						if ( ($attachment->ID == $thumbid) ) continue;

							$image_attributes = wp_get_attachment_image_src( $attachment_id, 'full' ); // returns an array
							$image            = aq_resize( $image_attributes[0], $image_size['width'], $image_size['height'], true );
							$image_title      = $attachment->post_title;

							if (!$attachment_counter && !has_post_thumbnail()) {
								if ($lightbox == "yes") {
									$link_href    = $image_attributes[0];
								} else {
									$link_href    = get_permalink($post->ID);
								}
								$figure_before = '<figure class="thumbnail thumbnail__portfolio">';
								$figure_after  = '</figure><!--/.thumbnail__portfolio-->';
								$link_style    = 'display:block';
								$img_tag       = '<img src="'.$image.'" alt="'.$image_title.'" />';
							} else {
								$figure_before = '';
								$figure_after  = '';
								$link_href = $image_attributes[0];
								$link_style = 'display:none';
								unset($img_tag);
								unset($zoom_icon);
							} ?>
					<?php echo $figure_before; ?><a href="<?php echo $link_href; ?>" class="image-wrap" title="<?php the_title(); ?>" style="<?php echo $link_style; ?>" <?php echo $link_rel; ?>><?php if (isset($img_tag)) echo $img_tag; if (isset($zoom_icon)) echo $zoom_icon; ?></a><?php echo $figure_after; ?>
					<?php $attachment_counter++;
					}
				}
			} ?>

			<div class="caption caption__portfolio">
				<?php if($folio_title == "yes"){ ?>
					<h3><a href="<?php the_permalink(); ?>"><?php $title = the_title('','',FALSE); echo mb_substr($title, 0, 40); ?></a></h3>
				<?php } ?>

				<?php if($folio_excerpt == "yes"){ ?>
					<p class="excerpt">
						<?php
							$excerpt = get_the_excerpt();
							echo wp_trim_words( $excerpt, $folio_excerpt_count );
						?>
					</p>
				<?php } ?>

				<?php if($folio_btn == "yes"){
					$button_text = of_get_option('folio_button_text') ? apply_filters( 'cherry_text_translate', of_get_option('folio_button_text'), 'folio_button_text' ) : theme_locals("read_more") ;
				?>
					<p><a href="<?php the_permalink() ?>" class="btn btn-primary"><?php echo $button_text ?></a></p>
				<?php } ?>
			</div><!--/.caption__portfolio-->

		</div><!--/.portfolio_item_holder-->
	</li><!--/.portfolio_item-->
	<?php $i++; endwhile; ?>