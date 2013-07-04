<?php /* Loop Name: Faq */ ?>
<?php
    $temp = $wp_query;
    $wp_query = null;
    $wp_query = new WP_Query();
    $wp_query->query('post_type=faq&showposts=-1');
?>
<dl class="faq-list">
<?php while ($wp_query->have_posts()) : $wp_query->the_post(); ?>
    <dt class="faq-list_h">
    	<span class="marker"><?php echo theme_locals("q"); ?></span><?php the_title(); ?>
    </dt>
    <dd id="post-<?php the_ID(); ?>" class="faq-list_body">
        <span class="marker"><?php echo theme_locals("a"); ?></span><?php the_content(); ?>
    </dd>
<?php endwhile; ?>
</dl>
<?php 
	$wp_query = null;
	$wp_query = $temp;
?>