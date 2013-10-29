<?php 
	get_header();

	$blog_sidebar_pos = of_get_option('blog_sidebar_pos');
	$blog_class = 'span8 '.$blog_sidebar_pos;
	$display_sidebar = true;
	$blog_before = $blog_after = '';

	switch ($blog_sidebar_pos) {
		case 'masonry':
			$blog_class = 'span12';
			$blog_before = '<div class="isotope">';
			$blog_after = '</div>';
			$display_sidebar = false;
		break;
		case 'none':
			$blog_class = 'span12';
			$display_sidebar = false;
		break;
	}
?>

<div class="motopress-wrapper content-holder clearfix">
	<div class="container">
		<div class="row">
			<div class="span12" data-motopress-wrapper-file="index.php" data-motopress-wrapper-type="content">
				<div class="row">
					<div class="span12" data-motopress-type="static" data-motopress-static-file="static/static-title.php">
						<?php get_template_part("static/static-title"); ?>
					</div>
				</div>
				<div class="row">
					<div class="<?php echo $blog_class ?>" id="content" data-motopress-type="loop" data-motopress-loop-file="loop/loop-blog.php">
						<?php
							echo $blog_before;
							get_template_part("loop/loop-blog");
							echo $blog_after;
						?>
						<?php get_template_part('includes/post-formats/post-nav'); ?>
					</div>
				<?php if($display_sidebar): ?>
					<div class="sidebar span4" id="sidebar" data-motopress-type="static-sidebar"  data-motopress-sidebar-file="sidebar.php">
						<?php get_sidebar(); ?>
					</div>
				<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</div>

<?php get_footer(); ?>