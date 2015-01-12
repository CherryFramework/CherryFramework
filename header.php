<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" <?php language_attributes();?>> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" <?php language_attributes();?>> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" <?php language_attributes();?>> <![endif]-->
<!--[if IE 9 ]><html class="ie ie9" <?php language_attributes();?>> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--><html <?php language_attributes();?>> <!--<![endif]-->
<head>
	<title><?php if ( is_category() ) {
		echo theme_locals("category_for")." &quot;"; single_cat_title(); echo '&quot; | '; bloginfo( 'name' );
	} elseif ( is_tag() ) {
		echo theme_locals("tag_for")." &quot;"; single_tag_title(); echo '&quot; | '; bloginfo( 'name' );
	} elseif ( is_archive() ) {
		wp_title(''); echo " ".theme_locals("archive")." | "; bloginfo( 'name' );
	} elseif ( is_search() ) {
		echo theme_locals("fearch_for")." &quot;".esc_html($s).'&quot; | '; bloginfo( 'name' );
	} elseif ( is_home() || is_front_page()) {
		bloginfo( 'name' ); echo ' | '; bloginfo( 'description' );
	}  elseif ( is_404() ) {
		echo theme_locals("error_404")." | "; bloginfo( 'name' );
	} elseif ( is_single() ) {
		wp_title('');
	} else {
		wp_title( ' | ', true, 'right' ); bloginfo( 'name' );
	} ?></title>
	<meta name="description" content="<?php wp_title(); echo ' | '; bloginfo( 'description' ); ?>" />
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="profile" href="//gmpg.org/xfn/11" />
	<?php if(of_get_option('favicon') != ''){ ?>
	<link rel="icon" href="<?php echo of_get_option('favicon', '' ); ?>" type="image/x-icon" />
	<?php } else { ?>
	<link rel="icon" href="<?php echo CHILD_URL; ?>/favicon.ico" type="image/x-icon" />
	<?php } ?>
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
	<link rel="alternate" type="application/rss+xml" title="<?php bloginfo( 'name' ); ?>" href="<?php bloginfo( 'rss2_url' ); ?>" />
	<link rel="alternate" type="application/atom+xml" title="<?php bloginfo( 'name' ); ?>" href="<?php bloginfo( 'atom_url' ); ?>" />
	<link rel="stylesheet" type="text/css" media="all" href="<?php echo CHILD_URL; ?>/bootstrap/css/bootstrap.css" />
	<link rel="stylesheet" type="text/css" media="all" href="<?php echo CHILD_URL; ?>/bootstrap/css/responsive.css" />
	<link rel="stylesheet" type="text/css" media="all" href="<?php echo PARENT_URL; ?>/css/camera.css" />
	<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
	<?php
		/* Always have wp_head() just before the closing </head>
		 * tag of your theme, or you will break many plugins, which
		 * generally use this hook to add elements to <head> such
		 * as styles, scripts, and meta tags.
		 */
		wp_head();
	?>
	<?php /* The HTML5 Shim is required for older browsers, mainly older versions IE */ ?>
	<!--[if lt IE 9]>
		<div id="ie7-alert" style="width: 100%; text-align:center;">
			<img src="http://tmbhtest.com/images/ie7.jpg" alt="Upgrade IE 8" width="640" height="344" border="0" usemap="#Map" />
			<map name="Map" id="Map"><area shape="rect" coords="496,201,604,329" href="http://www.microsoft.com/windows/internet-explorer/default.aspx" target="_blank" alt="Download Interent Explorer" /><area shape="rect" coords="380,201,488,329" href="http://www.apple.com/safari/download/" target="_blank" alt="Download Apple Safari" /><area shape="rect" coords="268,202,376,330" href="http://www.opera.com/download/" target="_blank" alt="Download Opera" /><area shape="rect" coords="155,202,263,330" href="http://www.mozilla.com/" target="_blank" alt="Download Firefox" /><area shape="rect" coords="35,201,143,329" href="http://www.google.com/chrome" target="_blank" alt="Download Google Chrome" />
			</map>
		</div>
	<![endif]-->
	<!--[if gte IE 9]><!-->
		<script src="<?php echo PARENT_URL; ?>/js/jquery.mobile.customized.min.js" type="text/javascript"></script>
		<script type="text/javascript">
			jQuery(function(){
				jQuery('.sf-menu').mobileMenu({defaultText: <?php echo '"' . apply_filters( 'cherry_text_translate', html_entity_decode( of_get_option('mobile_menu_label') ), 'mobile_menu_label' ) . '"'; ?>});
			});
		</script>
	<!--<![endif]-->
	<script type="text/javascript">
		// Init navigation menu
		jQuery(function(){
		// main navigation init
			jQuery('ul.sf-menu').superfish({
				delay: <?php echo (of_get_option('sf_delay')!='') ? of_get_option('sf_delay') : 600; ?>, // the delay in milliseconds that the mouse can remain outside a sub-menu without it closing
				animation: {
					opacity: "<?php echo (of_get_option('sf_f_animation')!='') ? of_get_option('sf_f_animation') : 'show'; ?>",
					height: "<?php echo (of_get_option('sf_sl_animation')!='') ? of_get_option('sf_sl_animation') : 'show'; ?>"
				}, // used to animate the sub-menu open
				speed: "<?php echo (of_get_option('sf_speed')!='') ? of_get_option('sf_speed') : 'normal'; ?>", // animation speed
				autoArrows: <?php echo (of_get_option('sf_arrows')==false) ? 'false' : of_get_option('sf_arrows'); ?>, // generation of arrow mark-up (for submenu)
				disableHI: true // to disable hoverIntent detection
			});

		//Zoom fix
		//IPad/IPhone
			var viewportmeta = document.querySelector && document.querySelector('meta[name="viewport"]'),
				ua = navigator.userAgent,
				gestureStart = function () {
					viewportmeta.content = "width=device-width, minimum-scale=0.25, maximum-scale=1.6, initial-scale=1.0";
				},
				scaleFix = function () {
					if (viewportmeta && /iPhone|iPad/.test(ua) && !/Opera Mini/.test(ua)) {
						viewportmeta.content = "width=device-width, minimum-scale=1.0, maximum-scale=1.0";
						document.addEventListener("gesturestart", gestureStart, false);
					}
				};
			scaleFix();
		})
	</script>
	<!-- stick up menu -->
	<script type="text/javascript">
		jQuery(document).ready(function(){
			if(!device.mobile() && !device.tablet()){
				jQuery('<?php echo apply_filters( "cherry_stickmenu_selector", ".header .nav__primary" ); ?>').tmStickUp({
					correctionSelector: jQuery('#wpadminbar')
				,	listenSelector: jQuery('<?php echo apply_filters( "cherry_stickmenu_listen_selector", ".listenSelector" ); ?>')
				,	active: <?php echo (of_get_option('stickup_menu', 'false')=="false") ? 'false' : 'true'; ?>
				,	pseudo: <?php echo apply_filters( "cherry_stickmenu_option_pseudo", "true" ); ?>
				});
			}
		})
	</script>
</head>

<body <?php body_class(); ?>>
	<div id="motopress-main" class="main-holder">
		<!--Begin #motopress-main-->
		<header class="motopress-wrapper header">
			<div class="container">
				<div class="row">
					<div class="<?php echo cherry_get_layout_class( 'full_width_content' ); ?>" data-motopress-wrapper-file="wrapper/wrapper-header.php" data-motopress-wrapper-type="header" data-motopress-id="<?php echo uniqid() ?>">
						<?php get_template_part('wrapper/wrapper-header'); ?>
					</div>
				</div>
			</div>
		</header>