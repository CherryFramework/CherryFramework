<?php
/**
* Template Name: Archives
*/

get_header(); ?>

<div class="motopress-wrapper content-holder clearfix">
    <div class="container">
        <div class="row">
            <div class="span12" data-motopress-wrapper-file="page-archives.php" data-motopress-wrapper-type="content">
                <div class="row">
                    <div class="span12" data-motopress-type="static" data-motopress-static-file="static/static-title.php">
                        <?php get_template_part("static/static-title"); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="span8 <?php echo of_get_option('blog_sidebar_pos') ?>" id="content" data-motopress-type="loop" data-motopress-loop-file="loop/loop-archives.php">
                        <?php get_template_part("loop/loop-archives"); ?>
                    </div>
                    <div class="span4 sidebar" id="sidebar" data-motopress-type="static-sidebar"  data-motopress-sidebar-file="sidebar.php">
						<?php get_sidebar(); ?>
					</div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>