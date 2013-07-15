<?php
/**
* Sidebar Name: Static Sidebar
*/
?>

<?php if ( ! dynamic_sidebar( theme_locals("sidebar") )) : ?>
	<div id="sidebar-search" class="widget">
		<?php echo '<h3>' . theme_locals("search") . '</h3>'; ?>
		<?php get_search_form(); ?> <!-- outputs the default Wordpress search form-->
	</div>

	<div id="sidebar-nav" class="widget menu">
		<?php echo '<h3>' . theme_locals("navigation") . '</h3>'; ?>
		<?php wp_nav_menu( array('menu' => 'Sidebar Menu' )); ?> <!-- editable within the Wordpress backend -->
	</div>

	<div id="sidebar-archives" class="widget">
		<?php echo '<h3>' . theme_locals("archives") . '</h3>'; ?>
		<ul>
			<?php wp_get_archives( 'type=monthly' ); ?>
		</ul>
	</div>

	<div id="sidebar-meta" class="widget">
		<?php echo '<h3>' . theme_locals("meta") . '</h3>'; ?>
		<ul>
			<?php wp_register(); ?>
			<li><?php wp_loginout(); ?></li>
			<?php wp_meta(); ?>
		</ul>
	</div>
<?php endif; ?>