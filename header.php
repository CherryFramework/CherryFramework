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
	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<?php if(of_get_option('favicon') != ''){ ?>
	<link rel="icon" href="<?php echo of_get_option('favicon', "" ); ?>" type="image/x-icon" />
	<?php } else { ?>
	<link rel="icon" href="<?php echo get_stylesheet_directory_uri(); ?>/favicon.ico" type="image/x-icon" />
	<?php } ?>
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
	<link rel="alternate" type="application/rss+xml" title="<?php bloginfo( 'name' ); ?>" href="<?php bloginfo( 'rss2_url' ); ?>" />
	<link rel="alternate" type="application/atom+xml" title="<?php bloginfo( 'name' ); ?>" href="<?php bloginfo( 'atom_url' ); ?>" />
	<?php 	
	/* The HTML5 Shim is required for older browsers, mainly older versions IE */ ?>
	<!--[if lt IE 8]>
	<div style=' clear: both; text-align:center; position: relative;'>
    	<a href="http://www.microsoft.com/windows/internet-explorer/default.aspx?ocid=ie6_countdown_bannercode"><img src="http://storage.ie6countdown.com/assets/100/images/banners/warning_bar_0000_us.jpg" border="0" alt="" /></a>
    </div>
	<![endif]-->
	
	<link rel="stylesheet" type="text/css" media="all" href="<?php echo get_stylesheet_directory_uri(); ?>/bootstrap/css/bootstrap.css" />
	<link rel="stylesheet" type="text/css" media="all" href="<?php echo get_stylesheet_directory_uri(); ?>/bootstrap/css/responsive.css" />	
	<link rel="stylesheet" type="text/css" media="all" href="<?php echo get_template_directory_uri(); ?>/css/prettyPhoto.css" />
	<link rel="stylesheet" type="text/css" media="all" href="<?php echo get_template_directory_uri(); ?>/css/camera.css" />
	<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
	
	<?php		
		/* Always have wp_head() just before the closing </head>
		 * tag of your theme, or you will break many plugins, which
		 * generally use this hook to add elements to <head> such
		 * as styles, scripts, and meta tags.
		 */
		wp_head();
	?>
	  
	<!--[if (gt IE 9)|!(IE)]><!-->
	<script src="<?php echo get_template_directory_uri(); ?>/js/jquery.mobile.customized.min.js" type="text/javascript"></script>
	<script type="text/javascript">
		jQuery(function(){
			jQuery('.sf-menu').mobileMenu({defaultText: <?php echo '"'.of_get_option('mobile_menu_label').'"'; ?>});
		});
	</script>
	<!--<![endif]-->
  
	<script type="text/javascript">
		// Init navigation menu
		jQuery(function(){
		// main navigation init
			jQuery('ul.sf-menu').superfish({
				delay:       <?php echo of_get_option('sf_delay'); ?>, 		// the delay in milliseconds that the mouse can remain outside a sub-menu without it closing
				animation:   {opacity:'<?php echo of_get_option('sf_f_animation'); ?>'<?php if (of_get_option('sf_sl_animation')=='show') { ?>,height:'<?php echo of_get_option('sf_sl_animation'); ?>'<?php } ?>}, // used to animate the sub-menu open
				speed:       '<?php echo of_get_option('sf_speed'); ?>',  // animation speed 
				autoArrows:  <?php echo of_get_option('sf_arrows'); ?>,   // generation of arrow mark-up (for submenu)
				disableHI: true // to disable hoverIntent detection
			});

		//Zoom fix
		//IPad/IPhone
		  	var viewportmeta = document.querySelector && document.querySelector('meta[name="viewport"]'),
		    	ua = navigator.userAgent,
		    	gestureStart = function () {
		        	viewportmeta.content = "width=device-width, minimum-scale=0.25, maximum-scale=1.6";
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
  
	<style type="text/css">
		<?php echo of_get_option('custom_css'); ?>

		<?php $background = of_get_option('body_background');
			if ($background != '') {
				if ($background['image'] != '') {
					echo 'body { background-image:url('.$background['image']. '); background-repeat:'.$background['repeat'].'; background-position:'.$background['position'].';  background-attachment:'.$background['attachment'].'; }';
				}
				if($background['color'] != '') {
					echo 'body { background-color:'.$background['color']. '}';
				}
			};
		?>
		
		<?php $header_styling = of_get_option('header_color'); 
			if($header_styling != '') {
				echo '.header {background-color:'.$header_styling.'}';
			}
		?>
	</style>
</head>

<body <?php body_class(); ?>>
	<div id="motopress-main" class="main-holder">
		<!--Begin #motopress-main-->
		<header class="motopress-wrapper header">
		    <div class="container">
		        <div class="row">
		            <div class="span12" data-motopress-wrapper-file="wrapper/wrapper-header.php" data-motopress-wrapper-type="header" data-motopress-id="<?php echo uniqid() ?>">
		                <?php get_template_part('wrapper/wrapper-header'); ?>
		            </div>
		        </div>
		    </div>
		</header>