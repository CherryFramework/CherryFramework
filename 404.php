<?php get_header(); ?>

<div class="motopress-wrapper content-holder clearfix">
    <div class="container">
        <div class="row">
            <div class="span12" data-motopress-wrapper-file="404.php" data-motopress-wrapper-type="content">
                <div class="row error404-holder">
                    <div class="span7 error404-holder_num" data-motopress-type="static" data-motopress-static-file="static/static-404.php">
                    	<?php get_template_part("static/static-404"); ?>
                    </div>
                    <div class="span5" data-motopress-type="static" data-motopress-static-file="static/static-not-found.php">
                    	<?php get_template_part("static/static-not-found"); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>