<?php get_header(); ?>

<div class="motopress-wrapper content-holder clearfix">
	<div class="container">
		<div class="row">
			<div class="<?php echo cherry_get_layout_class( 'full_width_content' ); ?>" data-motopress-wrapper-file="404.php" data-motopress-wrapper-type="content">
				<div class="row error404-holder">
					<div class="<?php echo cherry_get_layout_class( 'left_block' ); ?> error404-holder_num" data-motopress-type="static" data-motopress-static-file="static/static-404.php">
						<?php get_template_part("static/static-404"); ?>
					</div>
					<div class="<?php echo cherry_get_layout_class( 'right_block' ); ?>" data-motopress-type="static" data-motopress-static-file="static/static-not-found.php">
						<?php get_template_part("static/static-not-found"); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php get_footer(); ?>