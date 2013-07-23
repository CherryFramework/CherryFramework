<?php
add_action('widgets_init', 'ad_125_125_load_widgets');

function ad_125_125_load_widgets()
{
  register_widget('Ad_125_125_Widget');
}

class Ad_125_125_Widget extends WP_Widget {
  
  function Ad_125_125_Widget()
  {
	$widget_ops = array('classname' => 'ad_125_125', 'description' => theme_locals("add_125_125"));

    $control_ops = array('id_base' => 'ad_125_125-widget');

    $this->WP_Widget('ad_125_125-widget', theme_locals("add_125_125_desc"), $widget_ops, $control_ops);
  }
  
  function widget($args, $instance)
  {
    extract($args);

    ?>
    <ul class="banners clearfix unstyled">
      <?php
      $ads = array(1, 2, 3, 4);
      foreach($ads as $ad_count):
        if($instance['ad_125_img_'.$ad_count] && $instance['ad_125_link_'.$ad_count]):
      ?>
      <li class="banners_li">
        <span class="hold"><a href="<?php echo $instance['ad_125_link_'.$ad_count]; ?>"><img src="<?php echo $instance['ad_125_img_'.$ad_count]; ?>" alt="" class="banners_img"/></a></span>
      </li>
      <?php endif; endforeach; ?>
    </ul>
    <?php
  }
  
  function update($new_instance, $old_instance)
  {
    $instance = $old_instance;

    $instance['ad_125_img_1'] = $new_instance['ad_125_img_1'];
    $instance['ad_125_link_1'] = $new_instance['ad_125_link_1'];
    $instance['ad_125_img_2'] = $new_instance['ad_125_img_2'];
    $instance['ad_125_link_2'] = $new_instance['ad_125_link_2'];
    $instance['ad_125_img_3'] = $new_instance['ad_125_img_3'];
    $instance['ad_125_link_3'] = $new_instance['ad_125_link_3'];
    $instance['ad_125_img_4'] = $new_instance['ad_125_img_4'];
    $instance['ad_125_link_4'] = $new_instance['ad_125_link_4'];

    return $instance;
  }

  function form($instance)
  {
    /* Set up some default widget settings. */
    $defaults = array( 'ad_125_img_1' => '', 'ad_125_link_1' => '', 'ad_125_img_2' => '', 'ad_125_link_2' => '', 'ad_125_img_3' => '', 'ad_125_link_3' => '', 'ad_125_img_4' => '', 'ad_125_link_4' => '' );
    $instance = wp_parse_args( (array) $instance, $defaults ); ?>
    <p><strong><?php echo theme_locals("ad_1"); ?></strong></p>
    <p>
      <label for="<?php echo $this->get_field_id('ad_125_img_1'); ?>"><?php echo theme_locals("image_ad_link"); ?></label>
      <input class="widefat" style="width: 216px;" id="<?php echo $this->get_field_id('ad_125_img_1'); ?>" name="<?php echo $this->get_field_name('ad_125_img_1'); ?>" value="<?php echo $instance['ad_125_img_1']; ?>" type="text" />
    </p>
    <p>
      <label for="<?php echo $this->get_field_id('ad_125_link_1'); ?>"><?php echo theme_locals("ad_link"); ?></label>
      <input class="widefat" style="width: 216px;" id="<?php echo $this->get_field_id('ad_125_link_1'); ?>" name="<?php echo $this->get_field_name('ad_125_link_1'); ?>" value="<?php echo $instance['ad_125_link_1']; ?>" type="text" />
    </p>
    <p><strong><?php echo theme_locals("ad_2"); ?></strong></p>
    <p>
      <label for="<?php echo $this->get_field_id('ad_125_img_2'); ?>"><?php echo theme_locals("image_ad_link"); ?></label>
      <input class="widefat" style="width: 216px;" id="<?php echo $this->get_field_id('ad_125_img_2'); ?>" name="<?php echo $this->get_field_name('ad_125_img_2'); ?>" value="<?php echo $instance['ad_125_img_2']; ?>" type="text" />
    </p>
    <p>
      <label for="<?php echo $this->get_field_id('ad_125_link_2'); ?>"><?php echo theme_locals("ad_link"); ?></label>
      <input class="widefat" style="width: 216px;" id="<?php echo $this->get_field_id('ad_125_link_2'); ?>" name="<?php echo $this->get_field_name('ad_125_link_2'); ?>" value="<?php echo $instance['ad_125_link_2']; ?>" type="text" />
    </p>
    <p><strong><?php echo theme_locals("ad_3"); ?></strong></p>
    <p>
      <label for="<?php echo $this->get_field_id('ad_125_img_3'); ?>"><?php echo theme_locals("image_ad_link"); ?></label>
      <input class="widefat" style="width: 216px;" id="<?php echo $this->get_field_id('ad_125_img_3'); ?>" name="<?php echo $this->get_field_name('ad_125_img_3'); ?>" value="<?php echo $instance['ad_125_img_3']; ?>" type="text" />
    </p>
    <p>
      <label for="<?php echo $this->get_field_id('ad_125_link_3'); ?>"><?php echo theme_locals("ad_link"); ?></label>
      <input class="widefat" style="width: 216px;" id="<?php echo $this->get_field_id('ad_125_link_3'); ?>" name="<?php echo $this->get_field_name('ad_125_link_3'); ?>" value="<?php echo $instance['ad_125_link_3']; ?>" type="text" />
    </p>
    <p><strong><?php echo theme_locals("ad_4"); ?></strong></p>
    <p>
      <label for="<?php echo $this->get_field_id('ad_125_img_4'); ?>"><?php echo theme_locals("image_ad_link"); ?></label>
      <input class="widefat" style="width: 216px;" id="<?php echo $this->get_field_id('ad_125_img_4'); ?>" name="<?php echo $this->get_field_name('ad_125_img_4'); ?>" value="<?php echo $instance['ad_125_img_4']; ?>" type="text" />
    </p>
    <p>
      <label for="<?php echo $this->get_field_id('ad_125_link_4'); ?>"><?php echo theme_locals("ad_link"); ?></label>
      <input class="widefat" style="width: 216px;" id="<?php echo $this->get_field_id('ad_125_link_4'); ?>" name="<?php echo $this->get_field_name('ad_125_link_4'); ?>" value="<?php echo $instance['ad_125_link_4']; ?>" type="text" />
    </p>
  <?php
  }
}
?>