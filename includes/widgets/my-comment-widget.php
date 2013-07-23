<?php
// =============================== My Recent Comments Widget ====================================== //
class MY_CommentWidget extends WP_Widget_Recent_Comments {

	function MY_CommentWidget() {
		parent::WP_Widget('my-recent-comments', $name = theme_locals("recent_comments"));
	}
	
	function widget( $args, $instance ) {
		global $wpdb, $comments, $comment;

		extract($args, EXTR_SKIP);
		$title = apply_filters('widget_title', empty($instance['title']) ? theme_locals("recent_comments_decs") : $instance['title']);
		$comments_count = apply_filters('widget_title', empty($instance['comments_count']) ? 5 : $instance['comments_count']);
		$display_avatar = apply_filters('widget_display_avatar', empty($instance['display_avatar']) ? '' : 'on' );
		$avatar_size = apply_filters('widget_avatar_size', empty($instance['avatar_size']) ? '48' : $instance['avatar_size']);
		$display_author_name = apply_filters('widget_display_author_name', empty($instance['display_author_name']) ? '' : 'on' );
		$display_date = apply_filters('widget_display_date', empty($instance['display_date']) ? '' : 'on' );
		$display_post_title = apply_filters('widget_display_post_title', empty($instance['display_post_title']) ? '' : 'on' );
		$meta_format = apply_filters('widget_meta_format', empty($instance['meta_format']) ? 'none' : $instance['meta_format'] );

		if ( $comments_count < 1 ){
			$comments_count = 1;
		}else if ( $comments_count > 15 ){
			$comments_count = 15;
		}
		$comment_len = 100;

		if ( function_exists( 'wpml_get_language_information' ) ) {
			global $sitepress;
			$sql = "
				SELECT * FROM {$wpdb->comments}
				JOIN {$wpdb->prefix}icl_translations 
				ON {$wpdb->comments}.comment_post_id = {$wpdb->prefix}icl_translations.element_id 
				AND {$wpdb->prefix}icl_translations.element_type='post_post' 
				WHERE comment_approved = '1' 
				AND language_code = '".$sitepress->get_current_language()."' 
				ORDER BY comment_date_gmt DESC LIMIT {$comments_count}";
		} else {
			$sql = "
				SELECT * FROM $wpdb->comments 
				WHERE comment_approved = '1' 
				AND comment_type not in ('pingback','trackback') 
				ORDER BY comment_date_gmt 
				DESC LIMIT {$comments_count}";
		}

		if ( !$comments = wp_cache_get( 'recent_comments', 'widget' ) ) {
			$comments = $wpdb->get_results($sql);
			wp_cache_add( 'recent_comments', $comments, 'widget' );
		}

		$comments = array_slice( (array) $comments, 0, $comments_count );
?>
		<?php echo $before_widget; ?>
			<?php if ( $title ) echo $before_title . $title . $after_title; ?>
		<ul class="comments-custom unstyled"><?php
			if ( $comments ) : foreach ( (array) $comments as $comment) : ?>
			
			<li class="comments-custom_li">
				
				<?php if(function_exists('get_avatar') && $display_avatar == 'on') {
					echo '<figure class="thumbnail featured-thumbnail">'; 
					echo get_avatar( get_the_author_meta('email'), $avatar_size ); /* This avatar is the user's gravatar (http://gravatar.com) based on their administrative email address */ 
					echo '</figure>';
				} ?>
				<?php if($display_post_title == 'on') {
					$post_ID = $comment->comment_post_ID;
					$title_format = "";
					if($meta_format=="icons"){
						$title_format = '<i class="icon-link"></i>';
					} else  if($meta_format=="labels"){
						$title_format = '<span class="ladle">'.theme_locals("comment_in").':</span> ';
					}
					echo '<div class="meta_format">'.$title_format.'<h4 class="comments-custom_h_title"><a href="'.post_permalink($post_ID).'" title="'.get_post($post_ID)->post_title.'">'.get_post($post_ID)->post_title.'</a></h4></div>';
				}?>
				<?php if($display_author_name == 'on') { 
					$title_author_name = "";
					if($meta_format=="icons"){
						$title_author_name = '<i class="icon-user"></i>';
					} else  if($meta_format=="labels"){
						$title_author_name = '<span class="ladle">'.theme_locals("comment_author").':</span> ';
					}
					echo'<div class="meta_format">'.$title_author_name.'<h4 class="comments-custom_h_author">'.$comment->comment_author.'</h4></div>';
				}?>
				<?php if($display_date == 'on') {
					$title_date = "";
					if($meta_format=="icons"){
						$title_date = '<i class="icon-calendar"></i>';
					} else  if($meta_format=="labels"){
						$title_date = '<span class="ladle">'.theme_locals("comment_date").':</span> ';
					}
					$comment_date = get_comment_date();
					$comment_time = get_comment_time();
					echo '<div class="meta_format">'.$title_date.'<time datetime="'.date('Y-m-d\TH:i:s', strtotime($comment_date.$comment_time)).'">'.$comment_date.' '.$comment_time.'</time></div>';
				}?>
			<div class="clear"></div>
				<div class="comments-custom_txt">
					<a href="<?php echo get_comment_link( $comment->comment_ID ); ?>" title="<?php echo theme_locals("go_to_c"); ?>"><?php echo strip_tags(substr(apply_filters('get_comment_text', $comment->comment_content), 0, $comment_len)); if (strlen($comment->comment_content) > $comment_len) echo '...';?></a>
				</div>
			</li>
		<?php
			endforeach; endif;?>
		</ul>
		<?php echo $after_widget; ?>
<?php
	}

	/** @see WP_Widget::update */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		//Strip tags from title and name to remove HTML 
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['comments_count'] = strip_tags( $new_instance['comments_count'] );
		$instance['avatar_size'] = $new_instance['avatar_size'];
		$instance['display_author_name'] = $new_instance['display_author_name'];
		$instance['display_avatar'] = $new_instance['display_avatar'];
		$instance['display_date'] = $new_instance['display_date'];
		$instance['display_post_title'] = $new_instance['display_post_title'];
		$instance['meta_format'] = $new_instance['meta_format'];

		return $instance;
	}
	/** @see WP_Widget::form */
	function form($instance) {
		$defaults = array( 'title' => 'My Recent Comments', 'comments_count' => '5', 'display_avatar' => 'on', 'avatar_size' => '48',  'display_author_name' => 'on', 'display_date' => 'on', 'display_post_title' => 'on', 'meta_format' => 'none' );
		$instance = wp_parse_args( (array) $instance, $defaults );

		$title = esc_attr($instance['title']);
		$comments_count = esc_attr($instance['comments_count']);
		$avatar_size = esc_attr($instance['avatar_size']);
		$display_author_name = esc_attr($instance['display_author_name']);
		$display_avatar = esc_attr($instance['display_avatar']);
		$display_date = esc_attr($instance['display_date']);
		$display_post_title = esc_attr($instance['display_post_title']);
		$meta_format = esc_attr($instance['meta_format']);

		?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php echo theme_locals("title").":"; ?><input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label></p>
		<p><label for="<?php echo $this->get_field_id('comments_count'); ?>"><?php echo theme_locals("comments_count").":"; ?><input class="widefat" id="<?php echo $this->get_field_id('comments_count'); ?>" name="<?php echo $this->get_field_name('comments_count'); ?>" type="text" value="<?php echo $comments_count; ?>" /></label></p>
		<p><input class="checkbox" id="<?php echo $this->get_field_id('display_avatar'); ?>" name="<?php echo $this->get_field_name('display_avatar'); ?>" type="checkbox" <?php checked( $instance['display_avatar'], 'on' ); ?> /> <label for="<?php echo $this->get_field_id('display_avatar'); ?>"><?php echo theme_locals("display_avatar"); ?></label></p>
		<p><label for="<?php echo $this->get_field_id('avatar_size'); ?>"><?php echo theme_locals("avatar_size").":"; ?> 
			<select id="<?php echo $this->get_field_id('avatar_size'); ?>" name="<?php echo $this->get_field_name('avatar_size'); ?>" style="width:80px;" > 
				<option value="128" <?php echo ($avatar_size === '128' ? ' selected="selected"' : ''); ?>><?php echo "128x128";?></option>
				<option value="96" <?php echo ($avatar_size === '96' ? ' selected="selected"' : ''); ?>><?php echo "96x96";?></option>
				<option value="64" <?php echo ($avatar_size === '64' ? ' selected="selected"' : ''); ?>><?php echo "64x64"; ?></option>
				<option value="48" <?php echo ($avatar_size === '48' ? ' selected="selected"' : ''); ?>><?php echo "48x48"; ?></option>
				<option value="32" <?php echo ($avatar_size === '32' ? ' selected="selected"' : ''); ?>><?php echo "32x32"; ?></option>
			</select>
		</label></p>
		<p><input class="checkbox" id="<?php echo $this->get_field_id('display_author_name'); ?>" name="<?php echo $this->get_field_name('display_author_name'); ?>" type="checkbox" <?php checked( $instance['display_author_name'], 'on' ); ?> /> <label for="<?php echo $this->get_field_id('display_author_name'); ?>"><?php echo theme_locals("display_author_name"); ?></label></p>
		<p><input class="checkbox" id="<?php echo $this->get_field_id('display_date'); ?>" name="<?php echo $this->get_field_name('display_date'); ?>" type="checkbox" <?php checked( $instance['display_date'], 'on' ); ?> /> <label for="<?php echo $this->get_field_id('display_date'); ?>"><?php echo theme_locals("display_date"); ?></label></p>
		<p><input class="checkbox" id="<?php echo $this->get_field_id('display_post_title'); ?>" name="<?php echo $this->get_field_name('display_post_title'); ?>" type="checkbox" <?php checked( $instance['display_post_title'], 'on' ); ?> /> <label for="<?php echo $this->get_field_id('display_post_title'); ?>"><?php echo theme_locals("display_post_title"); ?></label></p>
		<p><label for="<?php echo $this->get_field_id('meta_format'); ?>"><?php echo theme_locals("meta_format").":"; ?><br />
			<select id="<?php echo $this->get_field_id('meta_format'); ?>" name="<?php echo $this->get_field_name('meta_format'); ?>" style="width:150px;" > 
				<option value="none" <?php echo ($meta_format === 'none' ? ' selected="selected"' : ''); ?>><?php echo theme_locals("none") ?></option>
				<option value="icons" <?php echo ($meta_format === 'icons' ? ' selected="selected"' : ''); ?>><?php echo theme_locals("icons") ?></option>
				<option value="labels" <?php echo ($meta_format === 'labels' ? ' selected="selected"' : ''); ?>><?php echo theme_locals("labels") ?></option>
			</select>
		</label></p>
		<?php
	}
}?>