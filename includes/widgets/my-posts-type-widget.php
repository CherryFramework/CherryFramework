<?php
/*
// =============================== My advanced cycle widget ======================================*/
class MY_PostsTypeWidget extends WP_Widget {

function MY_PostsTypeWidget() {
		$widget_ops = array('classname' => 'my_posts_type_widget', 'description' => theme_locals("advanced_cycle_desc"));
		$control_ops = array('width' => 500, 'height' => 350);
	    parent::WP_Widget(false, theme_locals("advanced_cycle"), $widget_ops, $control_ops);
}

/**
 * Displays custom posts widget on blog.
 */
function widget($args, $instance) {
	global $post;
	$post_old = $post; // Save the post object.
	
	extract( $args );
  if (isset($instance['excerpt_length']))
    $limit = apply_filters('widget_title', $instance['excerpt_length']);
  else
    $limit = 0;

	
  $valid_sort_orders = array('date', 'title', 'comment_count', 'rand');
  if ( in_array($instance['sort_by'], $valid_sort_orders) ) {
    $sort_by = $instance['sort_by'];
    $sort_order = (bool) $instance['asc_sort_order'] ? 'ASC' : 'DESC';
  } else {
    // by default, display latest first
    $sort_by = 'date';
    $sort_order = 'DESC';
  }
	
	// Get array of post info.
	
	$args = array(
		'showposts' => $instance["num"],
		'post_type' => $instance['posttype'],
		'orderby' => $sort_by,
		'order' => $sort_order,
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
	
  $cat_posts = new WP_Query($args);
	
	echo $before_widget;
	
	// Widget title
	// If title exist.
	if( $instance["title"] ) {
	echo $before_title;
		echo $instance["title"];
	echo $after_title;
    }

	// Posts list
    if($instance['container_class']==""){
	echo "<ul class='post-list unstyled'>\n";
	}else{
    echo "<ul class='post-list unstyled " .$instance['container_class'] ."'>\n";
    }
	
	$limittext = $limit;
	$posts_counter = 0;
	while ( $cat_posts->have_posts() )
	{
		$cat_posts->the_post(); $posts_counter++;
	?>
    <?php if ($instance['posttype'] == "testi") {
      $custom = get_post_custom($post->ID);
      $testiname = $custom["testimonial-name"][0];
      $testiurl = $custom["testimonial-url"][0];
    }
    $thumb = get_post_thumbnail_id();
      $img_url = wp_get_attachment_url( $thumb,'full'); //get img URL	
      $image = aq_resize( $img_url, $instance['thumb_w'], $instance['thumb_h'], true ); //resize & crop img
    ?>
		<li class="cat_post_item-<?php echo $posts_counter; ?> clearfix">
      <?php 
        if(has_post_thumbnail()) {
          if ($instance["thumb"]) : ?>
          <figure class="featured-thumbnail thumbnail">
          <?php if ( $instance['thumb_as_link'] ) : ?>
              <a href="<?php the_permalink() ?>">
            <?php endif; ?>
            <?php if($instance['thumb_w']!=="" || $instance['thumb_h']!==""){
              $thumb_w = $instance['thumb_w'];
              $thumb_h = $instance['thumb_h']; ?>
              <img src="<?php echo $image; ?>" width="<?php echo $thumb_w ?>" height="<?php echo $thumb_h ?>" alt="<?php the_title(); ?>" />
            <?php }else{?>
              <?php the_post_thumbnail(); ?>
            <?php }?>
            <?php if ( $instance['thumb_as_link'] ) : ?>
              </a>
            <?php endif; ?>
          </figure>
        <?php endif;
      }		
   
      if ( $instance['date'] ) : ?>
        <time datetime="<?php the_time('Y-m-d\TH:i'); ?>"><?php the_time('F j, Y'); ?><?php the_time() ?></time>
      <?php endif; ?>
      
      <?php if ( $instance['comment_num'] ) : ?>
        <span class="post-list_comment"><?php comments_number(); ?></span>
      <?php endif; ?>
         
      <?php if ( $instance['show_title'] ) : ?>
			  <h4 class="post-list_h"><a class="post-title" href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php if ( $instance['show_title_date'] ) {?>[<?php the_time('m-d-Y'); ?>]<?php }else{?><?php the_title(); ?><?php }?></a></h4>
			<?php endif; ?>
				
				
			<div class="excerpt">
        <?php if ( $instance['excerpt'] ) : ?>
	       <?php if($limittext=="" || $limittext==0){ ?>
		  <?php if ( $instance['excerpt_as_link'] ) : ?>
                <a href="<?php the_permalink() ?>">
              <?php endif; ?>
            <?php the_excerpt(); ?>
		  <?php if ( $instance['excerpt_as_link'] ) : ?>
                </a>
              <?php endif; ?>
          <?php }else{ ?>
		  <?php if ( $instance['excerpt_as_link'] ) : ?>
                <a href="<?php the_permalink() ?>">
              <?php endif; ?>
            <?php $excerpt = get_the_excerpt(); echo my_string_limit_words($excerpt,$limittext);?>
		  <?php if ( $instance['excerpt_as_link'] ) : ?>
                </a>
              <?php endif; ?>
          <?php } ?>
        <?php endif; ?>
      </div>
			<?php if ($instance['posttype'] == "testi") { ?>
        <div class="name-testi"><span class="user"><?php echo $testiname; ?></span>, <a href="<?php echo $testiurl; ?>"><?php echo $testiurl; ?></a></div>
      <?php }?>
      <?php if ( $instance['more_link'] ) : ?>
        <a href="<?php the_permalink() ?>" class="btn btn-primary <?php if($instance['more_link_class']!="") {echo $instance['more_link_class'];}else{ ?>link<?php } ?>"><?php if($instance['more_link_text']==""){ echo theme_locals("read_more"); }else{ ?><?php echo $instance['more_link_text']; ?><?php } ?></a>
      <?php endif; ?>
		</li><!--//.post-list_li -->
    
	<?php } ?>
	<?php echo "</ul>\n"; ?>
	<?php if ( $instance['global_link'] ) : ?>
	  <a href="<?php echo $instance['global_link_href']; ?>" class="btn btn-primary link_show_all"><?php if($instance['global_link_text']==""){ echo theme_locals("view_all"); }else{ ?><?php echo $instance['global_link_text']; ?><?php } ?></a>
	<?php endif; ?>
	
<?php 	
	echo $after_widget;
	
	$post = $post_old; // Restore the post object.
}

/**
 * Form processing.
 */
function update($new_instance, $old_instance) {
  $instance = $old_instance;
  $instance['asc_sort_order'] = strip_tags($new_instance['asc_sort_order']);
  $instance['thumb'] = strip_tags($new_instance['thumb']);
  $instance['thumb_as_link'] = strip_tags($new_instance['thumb_as_link']);
  $instance['excerpt_length'] = strip_tags($new_instance['excerpt_length']);
  $instance['sort_by'] = strip_tags($new_instance['sort_by']);
  $instance['num'] = strip_tags($new_instance['num']);
  $instance['posttype'] = strip_tags($new_instance['posttype']);
  $instance['title'] = strip_tags($new_instance['title']);
  $instance['container_class'] = strip_tags($new_instance['container_class']);
  $instance['thumb_w'] = strip_tags($new_instance['thumb_w']);
  $instance['thumb_h'] = strip_tags($new_instance['thumb_h']);
  $instance['date'] = strip_tags($new_instance['date']);
  $instance['comment_num'] = strip_tags($new_instance['comment_num']);
  $instance['show_title'] = strip_tags($new_instance['show_title']);
  $instance['show_title_date'] = strip_tags($new_instance['show_title_date']);
  $instance['excerpt'] = strip_tags($new_instance['excerpt']);
  $instance['excerpt_as_link'] = strip_tags($new_instance['excerpt_as_link']);
  $instance['more_link'] = strip_tags($new_instance['more_link']);
  $instance['more_link_class'] = strip_tags($new_instance['more_link_class']);
  $instance['more_link_text'] = strip_tags($new_instance['more_link_text']);
  $instance['global_link'] = strip_tags($new_instance['global_link']);
  $instance['global_link_href'] = strip_tags($new_instance['global_link_href']);
  $instance['global_link_text'] = strip_tags($new_instance['global_link_text']);
	return $instance;
}

/**
 * The configuration form.
 */
function form($instance) {
  /* Set up some default widget settings. */
  $defaults = array( 'title' => '', 'posttype' => '', 'num' => '', 'sort_by' => '', 'asc_sort_order' => '', 'comment_num' => '', 'date' => '', 'container_class' => '', 'show_title' => '', 'show_title_date' => '', 'excerpt' => '', 'excerpt_length' => '', 'excerpt_as_link' => '', 'more_link' => '', 'more_link_text' => '', 'more_link_class' => '', 'thumb' => '', 'thumb_w' => '', 'thumb_h' => '', 'thumb_as_link' => '', 'global_link' => '', 'global_link_text' => '', 'global_link_href' => '' );
  $instance = wp_parse_args( (array) $instance, $defaults );

  $sort_by = esc_attr($instance['sort_by']);
?>

  <p>
    <label for="<?php echo $this->get_field_id("title"); ?>">
        <?php echo theme_locals("title"); ?>:
        <input class="widefat" id="<?php echo $this->get_field_id("title"); ?>" name="<?php echo $this->get_field_name("title"); ?>" type="text" value="<?php echo esc_attr($instance["title"]); ?>" />
    </label>
  </p>
  <div style="width:230px; float:left; padding-right:20px; border-right:1px solid #c7c7c7;">
  <p>
      <label>
          <?php echo theme_locals("posts_type"); ?>:
          <?php $args=array(); ?>
          <select id="<?php echo $this->get_field_id('posttype'); ?>" name="<?php echo $this->get_field_name('posttype'); ?>" class="widefat" style="width:150px;">
              <?php foreach(get_post_types($args,'names') as $key => $post_type) { 
			  
			  $label_obj = get_post_type_object($post_type); 
              $labels = $label_obj->labels->name;
			  ?>
              
              <?php if ($key=='page' || $key=='revision' || $key=='attachment' || $key=='nav_menu_item' || $key=='optionsframework'){continue;} ?>
              <option<?php selected( $instance['posttype'], $post_type ); ?> value="<?php echo $post_type; ?>"><?php echo $labels; ?></option>
              <?php } ?>
          </select>
      </label>
  </p>
  <p>
      <label for="<?php echo $this->get_field_id("num"); ?>">
          <?php echo theme_locals("posts_num"); ?>:
          <input style="text-align: center;" id="<?php echo $this->get_field_id("num"); ?>" name="<?php echo $this->get_field_name("num"); ?>" type="text" value="<?php echo absint($instance["num"]); ?>" size='4' />
      </label>
</p>

<p>
  <label for="<?php echo $this->get_field_id("sort_by"); ?>">
  <?php echo theme_locals("sort_by") ?>:
    <select id="<?php echo $this->get_field_id("sort_by"); ?>" name="<?php echo $this->get_field_name("sort_by"); ?>">
      <?php
        $options = array('date', 'title', 'comment_count', 'rand');
            foreach ($options as $option) {
              echo '<option value="' . $option . '" id="' . $option . '"', $sort_by == $option ? ' selected="selected"' : '', '>', $option, '</option>';
            } ?>
    </select>
  </label>
</p>
  
<p>
  <label for="<?php echo $this->get_field_id("asc_sort_order"); ?>">
    <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id("asc_sort_order"); ?>" name="<?php echo $this->get_field_name("asc_sort_order"); ?>" value="1" <?php checked( (bool) $instance["asc_sort_order"], true ); ?> />
          <?php echo theme_locals("reverse_sort"); ?>
  </label>
</p>
<p>
  <label for="<?php echo $this->get_field_id("comment_num"); ?>">
      <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id("comment_num"); ?>" name="<?php echo $this->get_field_name("comment_num"); ?>"<?php checked( (bool) $instance["comment_num"], true ); ?> />
      <?php echo theme_locals("show_number"); ?>
  </label>
</p>

<p>
  <label for="<?php echo $this->get_field_id("date"); ?>">
      <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id("date"); ?>" name="<?php echo $this->get_field_name("date"); ?>"<?php checked( (bool) $instance["date"], true ); ?> />
      <?php echo theme_locals("show_meta"); ?>
  </label>
</p>

<p>
  <label for="<?php echo $this->get_field_id("container_class"); ?>">
    <?php echo theme_locals("container_class"); ?>:
    <input class="widefat" id="<?php echo $this->get_field_id("container_class"); ?>" name="<?php echo $this->get_field_name("container_class"); ?>" type="text" value="<?php echo esc_attr($instance["container_class"]); ?>" /> <span style="font-size:11px; color:#999;"><?php echo theme_locals("default_featured_custom"); ?></span>
  </label>
</p>

  <fieldset style="border:1px solid #F1F1F1; padding:10px 10px 0; margin-bottom:1em;">
  <legend style="padding:0 5px;"><?php echo theme_locals("post_title"); ?>:</legend>
  <p>
      <label for="<?php echo $this->get_field_id("show_title"); ?>">
          <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id("show_title"); ?>" name="<?php echo $this->get_field_name("show_title"); ?>"<?php checked( (bool) $instance["show_title"], true ); ?> />
          <?php echo theme_locals("show_post_title"); ?>
      </label>
  </p>
  <p>
      <label for="<?php echo $this->get_field_id("show_title_date"); ?>">
          <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id("show_title_date"); ?>" name="<?php echo $this->get_field_name("show_title_date"); ?>"<?php checked( (bool) $instance["show_title_date"], true ); ?> />
          <?php echo theme_locals("date_as_title"); ?>
      </label>
  </p>
  
  </fieldset>

  <fieldset style="border:1px solid #F1F1F1; padding:10px 10px 0; margin-bottom:1em;">
  <legend style="padding:0 5px;"><?php echo theme_locals("excerpt"); ?>:</legend>
  <p>
      <label for="<?php echo $this->get_field_id("excerpt"); ?>">
          <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id("excerpt"); ?>" name="<?php echo $this->get_field_name("excerpt"); ?>"<?php checked( (bool) $instance["excerpt"], true ); ?> />
          <?php echo theme_locals("post_excerpt"); ?>
      </label>
  </p>
  <p>
      <label for="<?php echo $this->get_field_id("excerpt_length"); ?>">
          <?php echo theme_locals("excerpt_length"); ?>
      </label>
      <input style="text-align: center;" type="text" id="<?php echo $this->get_field_id("excerpt_length"); ?>" name="<?php echo $this->get_field_name("excerpt_length"); ?>" value="<?php echo $instance["excerpt_length"]; ?>" size="3" />
  </p>
  <p>
      <label for="<?php echo $this->get_field_id("excerpt_as_link"); ?>">
          <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id("excerpt_as_link"); ?>" name="<?php echo $this->get_field_name("excerpt_as_link"); ?>"<?php checked( (bool) $instance["excerpt_as_link"], true ); ?> />
          <?php echo theme_locals("excerpt_link"); ?>
      </label>
  </p>
  </fieldset>
</div>
<div style="width:230px; float:left; padding-left:20px;">
  <fieldset style="border:1px solid #F1F1F1; padding:10px 10px 0; margin-bottom:1em;">
  <legend style="padding:0 5px;"><?php echo theme_locals("more_link"); ?>:</legend>
  <p>
      <label for="<?php echo $this->get_field_id("more_link"); ?>">
          <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id("more_link"); ?>" name="<?php echo $this->get_field_name("more_link"); ?>"<?php checked( (bool) $instance["more_link"], true ); ?> />
          <?php echo theme_locals("show_more_link"); ?>
      </label>
  </p>
  
  <p>
  <label for="<?php echo $this->get_field_id("more_link_text"); ?>">
    <?php echo theme_locals("link_text"); ?>:
    <input class="widefat" id="<?php echo $this->get_field_id("more_link_text"); ?>" name="<?php echo $this->get_field_name("more_link_text"); ?>" type="text" value="<?php echo esc_attr($instance["more_link_text"]); ?>" /> <span style="font-size:11px; color:#999;"><?php echo theme_locals("default_read_more"); ?></span>
  </label>
  </p>
  <p>
  <label for="<?php echo $this->get_field_id("more_link_class"); ?>">
    <?php echo theme_locals("link_class"); ?>:
    <input class="widefat" id="<?php echo $this->get_field_id("more_link_class"); ?>" name="<?php echo $this->get_field_name("more_link_class"); ?>" type="text" value="<?php echo esc_attr($instance["more_link_class"]); ?>" /> <span style="font-size:11px; color:#999;"><?php echo theme_locals("default_link"); ?></span>
  </label>
  </p>
  </fieldset>
  <fieldset style="border:1px solid #F1F1F1; padding:10px 10px 0; margin-bottom:1em;">
  <legend style="padding:0 5px;"><?php echo theme_locals("thumbnail_dimensions"); ?>:</legend>
  <?php if ( function_exists('the_post_thumbnail') && current_theme_supports("post-thumbnails") ) : ?>
  <p>
      <label for="<?php echo $this->get_field_id("thumb"); ?>">
          <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id("thumb"); ?>" name="<?php echo $this->get_field_name("thumb"); ?>" value="1" <?php checked( (bool) $instance["thumb"], true ); ?> />
          <?php echo theme_locals("show_post_thumbnail"); ?>
      </label>
  </p>
  <p>
          <label for="<?php echo $this->get_field_id("thumb_w"); ?>">
              <?php echo theme_locals("width"); ?>: &nbsp;&nbsp;<input class="widefat" style="width:40%;" type="text" id="<?php echo $this->get_field_id("thumb_w"); ?>" name="<?php echo $this->get_field_name("thumb_w"); ?>" value="<?php echo absint($instance["thumb_w"]); ?>" />
          </label>
  </p>
  <p>
          <label for="<?php echo $this->get_field_id("thumb_h"); ?>">
              <?php echo theme_locals("height"); ?>: <input class="widefat" style="width:40%;" type="text" id="<?php echo $this->get_field_id("thumb_h"); ?>" name="<?php echo $this->get_field_name("thumb_h"); ?>" value="<?php echo absint($instance["thumb_h"]); ?>" />
          </label>
  </p>
  <p>
      <label for="<?php echo $this->get_field_id("thumb_as_link"); ?>">
          <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id("thumb_as_link"); ?>" name="<?php echo $this->get_field_name("thumb_as_link"); ?>"<?php checked( (bool) $instance["thumb_as_link"], true ); ?> />
          <?php echo theme_locals("thumbnail_as_link"); ?>
      </label>
  </p>
  </fieldset>
  <fieldset style="border:1px solid #F1F1F1; padding:10px 10px 0; margin-bottom:1em;">
  <legend style="padding:0 5px;"><?php echo theme_locals("all_posts_link"); ?>:</legend>
  <p>
      <label for="<?php echo $this->get_field_id("global_link"); ?>">
          <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id("global_link"); ?>" name="<?php echo $this->get_field_name("global_link"); ?>"<?php checked( (bool) $instance["global_link"], true ); ?> />
          <?php echo theme_locals("show_global_link"); ?>
      </label>
  </p>
  <p>
  <label for="<?php echo $this->get_field_id("global_link_text"); ?>">
    <?php echo theme_locals("link_text"); ?>:
    <input class="widefat" id="<?php echo $this->get_field_id("global_link_text"); ?>" name="<?php echo $this->get_field_name("global_link_text"); ?>" type="text" value="<?php echo esc_attr($instance["global_link_text"]); ?>" /> <span style="font-size:11px; color:#999;"><?php echo theme_locals("default_view_all"); ?></span>
  </label>
  </p>
  <p>
      <label for="<?php echo $this->get_field_id("global_link_href"); ?>">
          <?php echo theme_locals("link_url"); ?>:
          <input class="widefat" id="<?php echo $this->get_field_id("global_link_href"); ?>" name="<?php echo $this->get_field_name("global_link_href"); ?>" type="text" value="<?php echo esc_attr($instance["global_link_href"]); ?>" />
      </label>
  </p>
  </fieldset>
</div>
<div style="clear:both;"></div>



		<?php endif; ?>

<?php

}

}
?>