<section class="title-section">
	<h1 class="title-header">
		<?php
			$shop_page = false;
			if(function_exists( 'is_shop' )){
				if(is_shop()){
					$shop_page = true;
				}
			}
			if(is_home()){ ?>
			<?php $blog_text = apply_filters( 'cherry_text_translate', of_get_option('blog_text'), 'blog_text' ); ?>
				<?php if($blog_text){?>
					<?php echo $blog_text; ?>
				<?php } else { ?>
					<?php echo theme_locals("blog"); ?>
			<?php } ?>

		<?php } elseif ( is_category() ) { ?>
			<?php printf( theme_locals("category_archives").": %s", '<small>' . single_cat_title( '', false ) . '</small>' ); ?>
			<?php echo category_description(); /* displays the category's description from the Wordpress admin */ ?>

		<?php } elseif ( is_tax('portfolio_category') ) { ?>
			<?php echo theme_locals("portfolio_category").": "; ?>
			<small><?php echo single_cat_title( '', false ); ?> </small>

		<?php } elseif ( is_search() ) { ?>
			<?php echo theme_locals("fearch_for").": ";?>"<?php the_search_query(); ?>"

		<?php } elseif ( is_day() ) { ?>
			<?php printf( theme_locals("daily_archives").": <small>%s</small>", get_the_date() ); ?>

		<?php } elseif ( is_month() ) { ?>
			<?php printf( theme_locals("monthly_archives").": <small>%s</small>", get_the_date('F Y') ); ?>

		<?php } elseif ( is_year() ) { ?>
			<?php printf( theme_locals("yearly_archives").": <small>%s</small>", get_the_date('Y') ); ?>

		<?php } elseif ( is_author() ) { ?>
			<?php
				global $author;
				$userdata = get_userdata($author);
			?>
				<?php echo theme_locals("by");?><?php echo $userdata->display_name; ?>

		<?php } elseif ( is_tag() ) { ?>
			<?php printf( theme_locals("tag_archives").": %s", '<small>' . single_tag_title( '', false ) . '</small>' ); ?>

		<?php } elseif ( is_tax('portfolio_tag') ) { ?>
			<?php echo theme_locals("portfolio_tag").": "; ?>
			<small><?php echo single_tag_title( '', false ); ?> </small>
<!--Begin shop-->
		<?php } elseif ($shop_page) {
				if (class_exists( 'Woocommerce' ) && !is_single()){
					$page_id = woocommerce_get_page_id('shop');
				} elseif (function_exists( 'jigoshop_init' ) && !is_singular()){
					$page_id = jigoshop_get_page_id('shop');
				}
				echo get_page($page_id)->post_title;
		?>
<!--End shop-->
		<?php } else { ?>
			<?php if (have_posts()) : while (have_posts()) : the_post();
				$pagetitle = get_post_custom_values("page-title");
				$pagedesc = get_post_custom_values("title-desc");
					if($pagetitle == ""){
						the_title();
					} else {
						echo $pagetitle[0];
					}
					if($pagedesc != ""){ ?>
						<span class="title-desc"><?php echo $pagedesc[0];?></span>
					<?php }
				endwhile; endif;
			wp_reset_query();
		} ?>
	</h1>
	<?php
		if (of_get_option('g_breadcrumbs_id') == 'yes') { ?>
			<!-- BEGIN BREADCRUMBS-->
			<?php
/* Begin shop */
				if (function_exists( 'is_shop' ) && is_shop() || function_exists( 'is_product' ) && is_product()){
					if(class_exists( 'Woocommerce' )){
						woocommerce_breadcrumb(array('delimiter' => ' / ', 'wrap_before' => '<ul class="breadcrumb breadcrumb__t">', 'wrap_after' => '</ul>'));
					} elseif(function_exists( 'jigoshop_init' )){
						jigoshop_breadcrumb('/ ', '<ul class="breadcrumb breadcrumb__t">', '</ul>');
					}
/* End shop */
				} elseif (function_exists('breadcrumbs')) {
					breadcrumbs();
				};
			?>
			<!-- END BREADCRUMBS -->
	<?php }
	?>
</section><!-- .title-section -->
