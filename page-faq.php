<?php
/**
* Template Name: FAQs
*/

get_header(); ?>

<div class="motopress-wrapper content-holder clearfix">
    <div class="container">
        <div class="row">
            <div class="span12" data-motopress-wrapper-file="page-faq.php" data-motopress-wrapper-type="content">
                <div class="row">
                    <div class="span12" data-motopress-type="static" data-motopress-static-file="static/static-title.php">
                        <?php get_template_part("static/static-title"); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="span12" id="content" data-motopress-type="loop" data-motopress-loop-file="loop/loop-faq.php">
                        <?php get_template_part("loop/loop-faq"); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>