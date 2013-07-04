<?php
// =============================== My Social Networks Widget ====================================== //
class My_SocialNetworksWidget extends WP_Widget {

	function My_SocialNetworksWidget() {
		$widget_ops = array('classname' => 'social_networks_widget', 'description' => theme_locals("social_networks_desc"));
		$this->WP_Widget('social_networks', theme_locals("social_networks"), $widget_ops);
	}

	function widget( $args, $instance ) {
		extract($args);
		$title = apply_filters('widget_title', $instance['title']);
		
		$networks['Twitter']['link'] = $instance['twitter'];
		$networks['Facebook']['link'] = $instance['facebook'];
		$networks['Flickr']['link'] = $instance['flickr'];
		$networks['Feed']['link'] = $instance['feed'];
		$networks['Linkedin']['link'] = $instance['linkedin'];
		$networks['Delicious']['link'] = $instance['delicious'];
		$networks['Youtube']['link'] = $instance['youtube'];
		$networks['Google+']['link'] = $instance['google'];
		
		$networks['Twitter']['label'] = $instance['twitter_label'];
		$networks['Facebook']['label'] = $instance['facebook_label'];
		$networks['Flickr']['label'] = $instance['flickr_label'];
		$networks['Feed']['label'] = $instance['feed_label'];
		$networks['Linkedin']['label'] = $instance['linkedin_label'];
		$networks['Delicious']['label'] = $instance['delicious_label'];
		$networks['Youtube']['label'] = $instance['youtube_label'];
		$networks['Google+']['label'] = $instance['google_label'];

		$display = $instance['display'];
		
		echo $before_widget;
		if ( $title )
			echo $before_title . $title . $after_title;
		?>
			<!-- BEGIN SOCIAL NETWORKS -->
			<?php if ($display == "both" or $display =="labels") {
				$addClass = "social__list";
			} elseif ($display == "icons") { 
				$addClass = "social__row clearfix";
			} ?>
			
			<ul class="social <?php echo $addClass ?> unstyled">
				
			<?php foreach(array("Facebook", "Twitter", "Flickr", "Feed", "Linkedin", "Delicious", "Youtube", "Google+") as $network) : ?>
	    		<?php if (!empty($networks[$network]['link'])) : ?>
				<li class="social_li">
					<a class="social_link social_link__<?php echo strtolower($network); ?>" rel="tooltip" data-original-title="<?php echo strtolower($network); ?>" href="<?php echo $networks[$network]['link']; ?>">
				    	<?php if (($display == "both") or ($display =="icons")) { ?>
							<span class="social_ico"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/icons/<?php echo strtolower($network);?>.png" alt=""></span>
						<?php } if (($display == "labels") or ($display == "both")) { ?> 
							<span class="social_label"><?php if (($networks[$network]['label'])!=="") { echo $networks[$network]['label']; } else { echo $network; } ?></span>
						<?php } ?>
					</a>
				</li>
				<?php endif; ?>
			<?php endforeach; ?>
			  
		</ul>
		<!-- END SOCIAL NETWORKS -->
	  
		<?php
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		
		$instance['twitter'] = $new_instance['twitter'];
		$instance['facebook'] = $new_instance['facebook'];
		$instance['flickr'] = $new_instance['flickr'];
		$instance['feed'] = $new_instance['feed'];
		$instance['linkedin'] = $new_instance['linkedin'];
		$instance['delicious'] = $new_instance['delicious'];
		$instance['youtube'] = $new_instance['youtube'];
		$instance['google'] = $new_instance['google'];
		
		$instance['twitter_label'] = $new_instance['twitter_label'];
		$instance['facebook_label'] = $new_instance['facebook_label'];
		$instance['flickr_label'] = $new_instance['flickr_label'];
		$instance['feed_label'] = $new_instance['feed_label'];
		$instance['linkedin_label'] = $new_instance['linkedin_label'];
		$instance['delicious_label'] = $new_instance['delicious_label'];
		$instance['youtube_label'] = $new_instance['youtube_label'];
		$instance['google_label'] = $new_instance['google_label'];

		$instance['display'] = $new_instance['display'];

		return $instance;
	}

	function form( $instance ) {
		/* Set up some default widget settings. */
		$defaults = array( 'title' => '', 'twitter' => '', 'twitter_label' => '', 'facebook' => '', 'facebook_label' => '', 'flickr' => '', 'flickr_label' => '', 'feed' => '', 'feed_label' => '', 'linkedin' => '', 'linkedin_label' => '', 'delicious' => '', 'delicious_label' => '', 'youtube' => '', 'youtube_label' => '', 'google' => '', 'google_label' => '', 'display' => 'icons', 'text' => '');
		$instance = wp_parse_args( (array) $instance, $defaults );
			
		$twitter = $instance['twitter'];		
		$facebook = $instance['facebook'];
		$flickr = $instance['flickr'];		
		$feed = $instance['feed'];
		$linkedin = $instance['linkedin'];	
		$delicious = $instance['delicious'];
		$youtube = $instance['youtube'];
		$google = $instance['google'];
		
		$twitter_label = $instance['twitter_label'];
		$facebook_label = $instance['facebook_label'];
		$flickr_label = $instance['flickr_label'];
		$feed_label = $instance['feed_label'];
		$linkedin_label = $instance['linkedin_label'];
		$delicious_label = $instance['delicious_label'];
		$youtube_label = $instance['youtube_label'];
		$google_label = $instance['google_label'];

		$display = $instance['display'];		
		$title = strip_tags($instance['title']);
		$text = format_to_edit($instance['text']);
?>
	<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php theme_locals("title") ?></label>
	<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>

	<fieldset style="border:1px solid #dfdfdf; padding:10px 10px 0; margin-bottom:1em;">
		<legend style="padding:0 5px;"><?php echo 'Facebook' ?>:</legend>
		
		<p><label for="<?php echo $this->get_field_id('facebook'); ?>"><?php echo 'Facebook URL:' ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('facebook'); ?>" name="<?php echo $this->get_field_name('facebook'); ?>" type="text" value="<?php echo esc_attr($facebook); ?>" /></p>
		
		<p><label for="<?php echo $this->get_field_id('facebook_label'); ?>"><?php echo 'Facebook '.theme_locals("label") ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('facebook_label'); ?>" name="<?php echo $this->get_field_name('facebook_label'); ?>" type="text" value="<?php echo esc_attr($facebook_label); ?>" /></p>
	</fieldset>	
	
	<fieldset style="border:1px solid #dfdfdf; padding:10px 10px 0; margin-bottom:1em;">
		<legend style="padding:0 5px;"><?php echo 'Twitter' ?>:</legend>	
	<p><label for="<?php echo $this->get_field_id('twitter'); ?>"><?php echo 'Twitter URL:' ?></label>
	<input class="widefat" id="<?php echo $this->get_field_id('twitter'); ?>" name="<?php echo $this->get_field_name('twitter'); ?>" type="text" value="<?php echo esc_attr($twitter); ?>" /></p>
	<p><label for="<?php echo $this->get_field_id('twitter_label'); ?>"><?php echo 'Twitter '.theme_locals("label") ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('twitter_label'); ?>" name="<?php echo $this->get_field_name('twitter_label'); ?>" type="text" value="<?php echo esc_attr($twitter_label); ?>" /></p>
	</fieldset>	
	
	<fieldset style="border:1px solid #dfdfdf; padding:10px 10px 0; margin-bottom:1em;">
		<legend style="padding:0 5px;"><?php echo 'Flickr' ?>:</legend>
	<p><label for="<?php echo $this->get_field_id('flickr'); ?>"><?php echo 'Flickr URL:' ?></label>
	<input class="widefat" id="<?php echo $this->get_field_id('flickr'); ?>" name="<?php echo $this->get_field_name('flickr'); ?>" type="text" value="<?php echo esc_attr($flickr); ?>" /></p>
	<p><label for="<?php echo $this->get_field_id('flickr_label'); ?>"><?php echo 'Flickr '.theme_locals("label") ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('flickr_label'); ?>" name="<?php echo $this->get_field_name('flickr_label'); ?>" type="text" value="<?php echo esc_attr($flickr_label); ?>" /></p>
	</fieldset>	
	
	<fieldset style="border:1px solid #dfdfdf; padding:10px 10px 0; margin-bottom:1em;">
		<legend style="padding:0 5px;"><?php echo 'RSS feed' ?>:</legend>
	<p><label for="<?php echo $this->get_field_id('feed'); ?>"><?php echo 'RSS feed:' ?></label>
	<input class="widefat" id="<?php echo $this->get_field_id('feed'); ?>" name="<?php echo $this->get_field_name('feed'); ?>" type="text" value="<?php echo esc_attr($feed); ?>" /></p>
	<p><label for="<?php echo $this->get_field_id('feed_label'); ?>"><?php echo 'RSS '.theme_locals("label") ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('feed_label'); ?>" name="<?php echo $this->get_field_name('feed_label'); ?>" type="text" value="<?php echo esc_attr($feed_label); ?>" /></p>
	</fieldset>	
	
	<fieldset style="border:1px solid #dfdfdf; padding:10px 10px 0; margin-bottom:1em;">
			<legend style="padding:0 5px;"><?php echo 'Linkedin' ?>:</legend>
	<p><label for="<?php echo $this->get_field_id('linkedin'); ?>"><?php echo 'Linkedin URL:' ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('linkedin'); ?>" name="<?php echo $this->get_field_name('linkedin'); ?>" type="text" value="<?php echo esc_attr($linkedin); ?>" /></p>
		<p><label for="<?php echo $this->get_field_id('linkedin_label'); ?>"><?php echo 'Linkedin '.theme_locals("label") ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('linkedin_label'); ?>" name="<?php echo $this->get_field_name('linkedin_label'); ?>" type="text" value="<?php echo esc_attr($linkedin_label); ?>" /></p>
		</fieldset>	
	
	<fieldset style="border:1px solid #dfdfdf; padding:10px 10px 0; margin-bottom:1em;">
			<legend style="padding:0 5px;"><?php echo 'Delicious' ?>:</legend>
	<p><label for="<?php echo $this->get_field_id('delicious'); ?>"><?php echo 'Delicious URL:' ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('delicious'); ?>" name="<?php echo $this->get_field_name('delicious'); ?>" type="text" value="<?php echo esc_attr($delicious); ?>" /></p>
		<p><label for="<?php echo $this->get_field_id('delicious_label'); ?>"><?php echo 'Delicious '.theme_locals("label") ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('delicious_label'); ?>" name="<?php echo $this->get_field_name('delicious_label'); ?>" type="text" value="<?php echo esc_attr($delicious_label); ?>" /></p>
		</fieldset>	
	
	<fieldset style="border:1px solid #dfdfdf; padding:10px 10px 0; margin-bottom:1em;">
		<legend style="padding:0 5px;"><?php echo 'Youtube' ?>:</legend>
		<p>
			<label for="<?php echo $this->get_field_id('youtube'); ?>"><?php echo 'Youtube URL:' ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('youtube'); ?>" name="<?php echo $this->get_field_name('youtube'); ?>" type="text" value="<?php echo esc_attr($youtube); ?>" /></p>
		<p>
			<label for="<?php echo $this->get_field_id('youtube_label'); ?>"><?php echo 'Youtube '.theme_locals("label") ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('youtube_label'); ?>" name="<?php echo $this->get_field_name('youtube_label'); ?>" type="text" value="<?php echo esc_attr($youtube_label); ?>" />
		</p>
	</fieldset>
	
	<fieldset style="border:1px solid #dfdfdf; padding:10px 10px 0; margin-bottom:1em;">
		<legend style="padding:0 5px;"><?php echo 'Google+'; ?>:</legend>
		<p>
			<label for="<?php echo $this->get_field_id('google'); ?>"><?php echo 'Google+ URL:'; ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('google'); ?>" name="<?php echo $this->get_field_name('google'); ?>" type="text" value="<?php echo esc_attr($google); ?>" /></p>
		<p>
			<label for="<?php echo $this->get_field_id('google_label'); ?>"><?php echo 'Google+ '.theme_locals("label"); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('google_label'); ?>" name="<?php echo $this->get_field_name('google_label'); ?>" type="text" value="<?php echo esc_attr($google_label); ?>" />
		</p>
	</fieldset>


		<p><?php echo theme_locals("display") ?></p>
		<label for="<?php echo $this->get_field_id('icons'); ?>"><input type="radio" name="<?php echo $this->get_field_name('display'); ?>" value="icons" id="<?php echo $this->get_field_id('icons'); ?>" <?php checked($display, "icons"); ?>></input>  <?php echo theme_locals("icons") ?></label>
		<label for="<?php echo $this->get_field_id('labels'); ?>"><input type="radio" name="<?php echo $this->get_field_name('display'); ?>" value="labels" id="<?php echo $this->get_field_id('labels'); ?>" <?php checked($display, "labels"); ?>></input> <?php echo theme_locals("labels") ?></label>
		<label for="<?php echo $this->get_field_id('both'); ?>"><input type="radio" name="<?php echo $this->get_field_name('display'); ?>" value="both" id="<?php echo $this->get_field_id('both'); ?>" <?php checked($display, "both"); ?>></input> <?php echo theme_locals("both") ?></label>


<?php
	}
}

add_action('widgets_init', create_function('', 'return register_widget("My_SocialNetworksWidget");'));
?>