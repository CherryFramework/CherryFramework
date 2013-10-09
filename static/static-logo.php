<?php /* Static Name: Logo */ ?>
<!-- BEGIN LOGO -->
<div class="logo pull-left">
	<?php if(of_get_option('logo_type') == 'text_logo'){?>
			<?php if( is_front_page() || is_home() || is_404() ) { ?>
					<h1 class="logo_h logo_h__txt"><a href="<?php echo home_url(); ?>/" title="<?php bloginfo('description'); ?>" class="logo_link"><?php bloginfo('name'); ?></a></h1>
			<?php } else { ?>
					<h2 class="logo_h logo_h__txt"><a href="<?php echo home_url(); ?>/" title="<?php bloginfo('description'); ?>" class="logo_link"><?php bloginfo('name'); ?></a></h2>
			<?php } ?>
	<?php } else { ?>
			<?php if(of_get_option('logo_url') == ''){ ?>
					<a href="<?php echo home_url(); ?>/" class="logo_h logo_h__img"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/logo.png" alt="<?php bloginfo('name'); ?>" title="<?php bloginfo('description'); ?>"></a>
			<?php } else  { ?>
					<a href="<?php echo home_url(); ?>/" class="logo_h logo_h__img"><img src="<?php echo of_get_option('logo_url', '' ); ?>" alt="<?php bloginfo('name'); ?>" title="<?php bloginfo('description'); ?>"></a>
			<?php }?>
	<?php }

	$tagline = get_bloginfo('description');
	if ( $tagline!='' ) { ?>
		<p class="logo_tagline"><?php bloginfo('description'); ?></p><!-- Site Tagline -->
	<?php } ?>
</div>
<!-- END LOGO -->