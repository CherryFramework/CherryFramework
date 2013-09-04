<?php /* Static Name: Navigation */ ?>
<!-- BEGIN MAIN NAVIGATION -->
<nav class="nav nav__primary clearfix">
<?php if (has_nav_menu('header_menu')) {
	wp_nav_menu( array(
		'container'      => 'ul',
		'menu_class'     => 'sf-menu',
		'menu_id'        => 'topnav',
		'depth'          => 0,
		'theme_location' => 'header_menu',
		'walker'         => new description_walker()
	));
} else {
	echo '<ul class="sf-menu">';
		$ex_page = get_page_by_title( 'Privacy Policy' );
		if ($ex_page === NULL) {
			$ex_page_id = '';
		} else {
			$ex_page_id = $ex_page->ID;
		}
		wp_list_pages( array(
			'depth'    => 0,
			'title_li' => '',
			'exclude'  => $ex_page_id
			)
		);
	echo '</ul>';
} ?>
</nav><!-- END MAIN NAVIGATION -->