<?php /* Loop Name: Author */ ?>
<?php
	if(isset($_GET['author_name'])) :
		$curauth = get_userdatabylogin($author_name);
	else :
		$curauth = get_userdata(intval($author));
	endif;
?>

<div class="post-author post-author__page clearfix">
	<h1 class="post-author_h"><?php echo theme_locals("about"); ?> <small><?php echo $curauth->display_name; ?></small></h1>
	<p class="post-author_gravatar">
		<?php if(function_exists('get_avatar')) { echo get_avatar( $curauth->user_email, $size = '65' ); } /* Displays the Gravatar based on the author's email address. Visit Gravatar.com for info on Gravatars */ ?>
	</p>

	<?php if($curauth->description !="") { /* Displays the author's description from their Wordpress profile */ ?>
		<div class="post-author_desc">
			<?php echo $curauth->description; ?>
		</div>
	<?php } ?>
</div><!--.post-author-->

<div id="recent-author-posts">
	<h3><?php echo theme_locals("recent_posts_by"); ?> <?php echo $curauth->display_name; ?></h3>
	<?php
		if (have_posts()) : while (have_posts()) : the_post();
			// The following determines what the post format is and shows the correct file accordingly
			$format = get_post_format();
			get_template_part( 'includes/post-formats/'.$format );

			if($format == '')
				get_template_part( 'includes/post-formats/standard' );
			endwhile; else:
	?>
		<div class="no-results">
			<?php echo '<p><strong>' . theme_locals("no_post_yet") . '</strong></p>'; ?>
		</div><!--.no-results-->
	<?php endif; ?>
</div><!--recent-author-posts-->

<?php get_template_part('includes/post-formats/post-nav'); ?>

<div id="recent-author-comments">
	<h3><?php echo theme_locals("recent_comments_by"); ?> <?php echo $curauth->display_name; ?></h3>
	<?php
		$number = 5; // number of recent comments to display

		if ( function_exists( 'wpml_get_language_information' ) ) {
			global $sitepress;
			$sql = "
				SELECT * FROM {$wpdb->comments}
				JOIN {$wpdb->prefix}icl_translations
				ON {$wpdb->comments}.comment_post_id = {$wpdb->prefix}icl_translations.element_id
				AND {$wpdb->prefix}icl_translations.element_type='post_post'
				WHERE comment_approved = '1'
				AND language_code = '".$sitepress->get_current_language()."'
				ORDER BY comment_date_gmt DESC LIMIT {$number}";
		} else {
			$sql = "
				SELECT * FROM $wpdb->comments
				WHERE comment_approved = '1'
				AND comment_author_email='$curauth->user_email'
				ORDER BY comment_date_gmt
				DESC LIMIT {$number}";
		}
		$comments = $wpdb->get_results($sql);

		if ( $comments ) : ?>

			<ul>

			<?php foreach ( (array) $comments as $comment) { ?>

				<li class="recentcomments">
					<?php printf( theme_locals("no_comments_author"), get_comment_date(), '<a href="'. get_comment_link( $comment->comment_ID ) . '">' . get_the_title( $comment->comment_post_ID ) . '</a>' ); ?>
				</li>

			<?php } ?>

			</ul>

		<?php else: ?>

			<p><?php echo theme_locals("no_comments_by"); ?> <?php echo $curauth->display_name; ?> <?php echo theme_locals("yet");?></p>

		<?php endif; ?>
</div><!--recent-author-comments-->