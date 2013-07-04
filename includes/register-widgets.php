<?php
/**
 * Loads up all the widgets defined by this theme. Note that this function will not work for versions of WordPress 2.7 or lower
 *
 */
get_template_part('includes/widgets/my-recent-posts');
get_template_part('includes/widgets/my-comment-widget');
get_template_part('includes/widgets/my-social-widget');
get_template_part('includes/widgets/my-posts-type-widget');
get_template_part('includes/widgets/my-flickr-widget');
get_template_part('includes/widgets/my-banners-widget');
get_template_part('includes/widgets/my-vcard-widget');

function load_my_widgets() {
	register_widget("MY_PostWidget");
	register_widget("MY_CommentWidget");
	register_widget("My_SocialNetworksWidget");
	register_widget("MY_PostsTypeWidget");
	register_widget("MY_FlickrWidget");
	register_widget("Ad_125_125_Widget");
	register_widget("MY_Vcard_Widget");
}
add_action("widgets_init", "load_my_widgets");
?>