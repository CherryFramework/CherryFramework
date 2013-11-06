<?php
/**
* Template Name: Filter Folio 4 cols
*/

get_header(); ?>

<div class="motopress-wrapper content-holder clearfix">
	<div class="container">
		<div class="row">
			<div class="<?php echo cherry_get_layout_class( 'full_width_content' ); ?>" data-motopress-wrapper-file="page-Portfolio4Cols-filterable.php" data-motopress-wrapper-type="content">
				<div class="row">
					<div class="<?php echo cherry_get_layout_class( 'full_width_content' ); ?>" data-motopress-type="static" data-motopress-static-file="static/static-title.php">
						<?php get_template_part("static/static-title"); ?>
					</div>
				</div>
				<div id="content" class="row">
					<div class="<?php echo cherry_get_layout_class( 'full_width_content' ); ?>" data-motopress-type="loop" data-motopress-loop-file="loop/loop-portfolio4.php">
						<?php get_template_part("loop/loop-portfolio4"); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php get_footer(); ?>