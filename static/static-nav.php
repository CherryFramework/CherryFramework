<?php /* Static Name: Navigation */ 
	if (has_nav_menu('header_menu')) { ?>
		<!-- BEGIN MAIN NAVIGATION -->
		<nav class="nav nav__primary clearfix">
			<?php wp_nav_menu( array(
				'container'		=> 'ul', 
				'menu_class'    => 'sf-menu', 
				'menu_id'       => 'topnav',
				'depth'         => 0,
				'theme_location'=> 'header_menu',
				'walker'		=> new description_walker()
			)); ?>
		</nav>
<?php } else {
	echo '<ul class="sf-menu">';
		wp_list_pages( array(
			'depth' => 0, 
			'title_li' => '' )
		);
	echo '</ul>';  
} ?><!-- END MAIN NAVIGATION -->