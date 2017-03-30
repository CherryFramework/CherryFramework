<?php
// Do not delete these lines
	if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die (theme_locals("please_do_not"));

	if ( post_password_required() ) { ?>
	<?php echo '<p class="nocomments">' . theme_locals("password") . '</p>'; ?>
	<?php
		return;
	}
?>
<!-- BEGIN Comments -->
<?php if ( have_comments() ) : ?>

	<div id="comments" class="comment-holder">
		<h3 class="comments-h"><?php printf( _n( theme_locals("response"), theme_locals("responses"), get_comments_number(), CURRENT_THEME ),
				number_format_i18n( get_comments_number() ), '' );?></h3>

		<div class="pagination">
			<?php paginate_comments_links('prev_text=Prev&next_text=Next'); ?>
		</div>

		<ol class="comment-list clearfix">
			<?php wp_list_comments('type=all&callback=mytheme_comment'); ?>
		</ol>

		<div class="pagination">
			<?php paginate_comments_links('prev_text=Prev&next_text=Next'); ?>
		</div>
	</div>

<?php else : // this is displayed if there are no comments so far

	if ( comments_open() ) {
		echo '<p class="nocomments">' . theme_locals( 'no_comments_yet' ) . '</p>';

	} else { // comments are closed
		echo '<p class="nocomments">' . theme_locals( 'comments_are_closed' ) . '</p>';
	}

endif; // Check for have_comments().

comment_form(); ?>