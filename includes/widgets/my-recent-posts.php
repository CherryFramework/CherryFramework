<?php
// =============================== My Recent Posts (News widget) ======================================
class MY_PostWidget extends WP_Widget {
    /** constructor */
    function MY_PostWidget() {
        parent::WP_Widget(false, $name = theme_locals("recent_posts"));	
    }

  	/** @see WP_Widget::widget */
    function widget($args, $instance) {		
        extract( $args );
        $title = apply_filters('widget_title', $instance['title']);
		$category = apply_filters('widget_category', $instance['category']);
		$post_format = apply_filters('widget_post_format', $instance['post_format']);
		$linktext = apply_filters('widget_linktext', $instance['linktext']);
		$linkurl = apply_filters('widget_linkurl', $instance['linkurl']);
		$count = apply_filters('widget_count', $instance['count']);
		$sort_by = apply_filters('widget_sort_by', $instance['sort_by']);
		$excerpt_count = apply_filters('widget_excerpt_count', $instance['excerpt_count']);
    ?>
              <?php echo $before_widget; ?>
                  <?php if ( $title )
                        echo $before_title . $title . $after_title; ?>
						
						<?php if($post_format == 'post-format-standard') { 
						
							$args = array(
										'showposts' => $count,
										'category_name' => $category,
										'orderby' => $sort_by,
										'tax_query' => array(
										 'relation' => 'AND',
											array(
												'taxonomy' => 'post_format',
												'field' => 'slug',
												'terms' => array('post-format-aside', 'post-format-gallery', 'post-format-link', 'post-format-image', 'post-format-quote', 'post-format-audio', 'post-format-video'),
												'operator' => 'NOT IN'
											)
										)
										);
						
						} else { 
						
							$args = array(
								'showposts' => $count,
								'category_name' => $category,
								'orderby' => $sort_by,
								'tax_query' => array(
								 'relation' => 'AND',
									array(
										'taxonomy' => 'post_format',
										'field' => 'slug',
										'terms' => array($post_format)
									)
								)
							);
						
						} ?>
						
						
						
						
						
						<?php $wp_query = new WP_Query( $args ); ?>
						
								<ul class="post-list unstyled">
								
								<?php if ($wp_query->have_posts()) : while ($wp_query->have_posts()) : $wp_query->the_post();?>
								
								<li class="post-list_li clearfix">
								
									<?php if(has_post_thumbnail()) { ?>
										<?php
										$thumb = get_post_thumbnail_id();
										$img_url = wp_get_attachment_url( $thumb,'full'); //get img URL
										$image = aq_resize( $img_url, 100, 100, true ); //resize & crop img
										?>
										<figure class="featured-thumbnail thumbnail">
											<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><img src="<?php echo $image ?>" alt="<?php the_title(); ?>" /></a>
										</figure>
									<?php } ?>
									
			                  <time datetime="<?php the_time('Y-m-d\TH:i'); ?>"><?php the_time('F j, Y'); ?></time>
			                  
			                  <h4 class="post-list_h"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php echo theme_locals("permanent_link_to"); ?> <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h4>
			                  
									<?php if($excerpt_count!="") { ?>
									<div class="excerpt">
                  				<?php $excerpt = get_the_excerpt(); echo my_string_limit_words($excerpt,$excerpt_count);?>
									</div>
									<?php } ?>
									<a href="<?php the_permalink() ?>" class="btn btn-primary"><?php echo theme_locals("read_more"); ?></a>
								</li>
								<?php endwhile; ?>
								</ul>
								<?php endif; ?>
								
								<?php $wp_query = null;?>
								
								
								<!-- Link under post cycle -->
								<?php if($linkurl !=""){?>
									<a href="<?php echo $linkurl; ?>" class="btn btn-primary"><?php echo $linktext; ?></a>
								<?php } ?>

								
              <?php echo $after_widget; ?>
			 
        <?php
    }

    /** @see WP_Widget::update */
    function update($new_instance, $old_instance) {				
        return $new_instance;
    }

    /** @see WP_Widget::form */
    function form($instance) {				
    	/* Set up some default widget settings. */
		$defaults = array( 'title' => '', 'category' => '', 'post_format' => '', 'linktext' => '', 'linkurl' => '', 'count' => '', 'sort_by' => '', 'excerpt_count' => '' );
		$instance = wp_parse_args( (array) $instance, $defaults );

      	$title = esc_attr($instance['title']);
		$category = esc_attr($instance['category']);
		$post_format = esc_attr($instance['post_format']);
		$linktext = esc_attr($instance['linktext']);
		$linkurl = esc_attr($instance['linkurl']);
		$count = esc_attr($instance['count']);
		$sort_by = esc_attr($instance['sort_by']);
		$excerpt_count = esc_attr($instance['excerpt_count']);		
    ?>
      	<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php echo theme_locals("title") ?><input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label></p>

      	<p><label for="<?php echo $this->get_field_id('category'); ?>"><?php echo theme_locals("category_slug") ?><input class="widefat" id="<?php echo $this->get_field_id('category'); ?>" name="<?php echo $this->get_field_name('category'); ?>" type="text" value="<?php echo $category; ?>" /></label></p>
			
		<p><label for="<?php echo $this->get_field_id('post_format'); ?>"><?php echo theme_locals("post_format") ?><br />

	      	<select id="<?php echo $this->get_field_id('post_format'); ?>" name="<?php echo $this->get_field_name('post_format'); ?>" style="width:150px;" > 
				<option value="post-format-standard" <?php echo ($post_format === 'post-format-standard' ? ' selected="selected"' : ''); ?>><?php echo theme_locals("standard") ?></option>
	      		<option value="post-format-aside" <?php echo ($post_format === 'post-format-aside' ? ' selected="selected"' : ''); ?>><?php echo theme_locals("aside") ?></option>
				<option value="post-format-quote" <?php echo ($post_format === 'post-format-quote' ? ' selected="selected"' : ''); ?> ><?php echo theme_locals("quote") ?></option>
				<option value="post-format-link" <?php echo ($post_format === 'post-format-link' ? ' selected="selected"' : ''); ?> ><?php echo theme_locals("link") ?></option>
				<option value="post-format-image" <?php echo ($post_format === 'post-format-image' ? ' selected="selected"' : ''); ?> ><?php echo theme_locals("image") ?></option>
	      		<option value="post-format-gallery" <?php echo ($post_format === 'post-format-gallery' ? ' selected="selected"' : ''); ?> ><?php echo theme_locals("gallery") ?></option>
				<option value="post-format-audio" <?php echo ($post_format === 'post-format-audio' ? ' selected="selected"' : ''); ?> ><?php echo theme_locals("audio") ?></option>
				<option value="post-format-video" <?php echo ($post_format === 'post-format-video' ? ' selected="selected"' : ''); ?> ><?php echo theme_locals("video") ?></option>
	      	</select>
      	</label></p>
      
      	<p><label for="<?php echo $this->get_field_id('sort_by'); ?>"><?php echo  theme_locals("post_order") ?><br />

	      	<select id="<?php echo $this->get_field_id('sort_by'); ?>" name="<?php echo $this->get_field_name('sort_by'); ?>" style="width:150px;" > 
				<option value="date" <?php echo ($sort_by === 'date' ? ' selected="selected"' : ''); ?>><?php echo theme_locals("date") ?></option>
		   	   	<option value="title" <?php echo ($sort_by === 'title' ? ' selected="selected"' : ''); ?>><?php echo theme_locals("title") ?></option>
		      	<option value="comment_count" <?php echo ($sort_by === 'comment_count' ? ' selected="selected"' : ''); ?>><?php echo theme_locals("comment_count") ?></option>
		      	<option value="rand" <?php echo ($sort_by === 'rand' ? ' selected="selected"' : ''); ?>><?php echo theme_locals("rand") ?></option>
	      	</select>
      	</label></p>
      
      	<p><label for="<?php echo $this->get_field_id('count'); ?>"><?php echo  theme_locals("posts_per_page") ?><input class="widefat" style="width:30px; display:block; text-align:center" id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>" type="text" value="<?php echo $count; ?>" /></label></p>
			
		<p><label for="<?php echo $this->get_field_id('excerpt_count'); ?>"><?php echo theme_locals("excerpt_length") ?><input class="widefat" style="width:30px; display:block; text-align:center" id="<?php echo $this->get_field_id('excerpt_count'); ?>" name="<?php echo $this->get_field_name('excerpt_count'); ?>" type="text" value="<?php echo $excerpt_count; ?>" /></label></p>
			
		 <p><label for="<?php echo $this->get_field_id('linktext'); ?>"><?php echo theme_locals("link_text") ?> <input class="widefat" id="<?php echo $this->get_field_id('linktext'); ?>" name="<?php echo $this->get_field_name('linktext'); ?>" type="text" value="<?php echo $linktext; ?>" /></label></p>
			 
		 <p><label for="<?php echo $this->get_field_id('linkurl'); ?>"><?php echo theme_locals("link_url") ?> <input class="widefat" id="<?php echo $this->get_field_id('linkurl'); ?>" name="<?php echo $this->get_field_name('linkurl'); ?>" type="text" value="<?php echo $linkurl; ?>" /></label></p>
        <?php 
    }
} // class Widget
?>