<?php
/**
* Template Name: Home Page
*/

get_header(); ?>

<div class="motopress-wrapper content-holder clearfix">
	<div class="container">
		<div class="row">
			<div class="span12" data-motopress-wrapper-file="page-home.php" data-motopress-wrapper-type="content">
				<div class="row">
					<div class="span12" data-motopress-type="static" data-motopress-static-file="static/static-slider.php">
						<?php get_template_part("static/static-slider"); ?>
					</div>
				</div>
				<div class="row">
					<div class="span12" data-motopress-type="loop" data-motopress-loop-file="loop/loop-page.php">
						<?php get_template_part("loop/loop-page"); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php get_footer(); ?>