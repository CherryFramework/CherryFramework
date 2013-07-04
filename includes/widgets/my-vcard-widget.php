<?php

class MY_Vcard_Widget extends WP_Widget {
  function MY_Vcard_Widget() {
    $widget_ops = array('classname' => 'widget_cherry_vcard', 'description' => theme_locals("vCard_desc"));
    $this->WP_Widget('widget_cherry_vcard', theme_locals("vCard_name"), $widget_ops);
    $this->alt_option_name = 'widget_cherry_vcard';

    add_action('save_post', array(&$this, 'flush_widget_cache'));
    add_action('deleted_post', array(&$this, 'flush_widget_cache'));
    add_action('switch_theme', array(&$this, 'flush_widget_cache'));
  }

  function widget($args, $instance) {
    $cache = wp_cache_get('widget_cherry_vcard', 'widget');

    if (!is_array($cache)) {
      $cache = array();
    }

    if (!isset($args['widget_id'])) {
      $args['widget_id'] = null;
    }

    if (isset($cache[$args['widget_id']])) {
      echo $cache[$args['widget_id']];
      return;
    }

    ob_start();
    extract($args, EXTR_SKIP);

    $title = apply_filters('widget_title', empty($instance['title']) ? theme_locals("vCard") : $instance['title'], $instance, $this->id_base);
    if (!isset($instance['street_address'])) { $instance['street_address'] = ''; }
    if (!isset($instance['locality'])) { $instance['locality'] = ''; }
    if (!isset($instance['region'])) { $instance['region'] = ''; }
    if (!isset($instance['postal_code'])) { $instance['postal_code'] = ''; }
    if (!isset($instance['tel'])) { $instance['tel'] = ''; }
    if (!isset($instance['email'])) { $instance['email'] = ''; }

    echo $before_widget;
    if ($title) {
      echo $before_title;
      echo $title;
      echo $after_title;
    }
  ?>
    <address class="vcard">
      <strong class="adr">
        <span class="street-address"><?php echo $instance['street_address']; ?></span>
        <span class="locality"><?php echo $instance['locality']; ?></span>
        <span class="region"><?php echo $instance['region']; ?></span>
        <span class="postal-code"><?php echo $instance['postal_code']; ?></span>
      </strong>
      <span class="tel"><span class="value"><?php echo $instance['tel']; ?></span></span>
      <a class="email" href="mailto:<?php echo $instance['email']; ?>"><?php echo $instance['email']; ?></a>
    </address>
  <?php
    echo $after_widget;

    $cache[$args['widget_id']] = ob_get_flush();
    wp_cache_set('widget_cherry_vcard', $cache, 'widget');
  }

  function update($new_instance, $old_instance) {
    $instance = $old_instance;
    $instance['title'] = strip_tags($new_instance['title']);
    $instance['street_address'] = strip_tags($new_instance['street_address']);
    $instance['locality'] = strip_tags($new_instance['locality']);
    $instance['region'] = strip_tags($new_instance['region']);
    $instance['postal_code'] = strip_tags($new_instance['postal_code']);
    $instance['tel'] = strip_tags($new_instance['tel']);
    $instance['email'] = strip_tags($new_instance['email']);
    $this->flush_widget_cache();

    $alloptions = wp_cache_get('alloptions', 'options');
    if (isset($alloptions['widget_cherry_vcard'])) {
      delete_option('widget_cherry_vcard');
    }

    return $instance;
  }

  function flush_widget_cache() {
    wp_cache_delete('widget_cherry_vcard', 'widget');
  }

  function form($instance) {
    $title = isset($instance['title']) ? esc_attr($instance['title']) : '';
    $street_address = isset($instance['street_address']) ? esc_attr($instance['street_address']) : '';
    $locality = isset($instance['locality']) ? esc_attr($instance['locality']) : '';
    $region = isset($instance['region']) ? esc_attr($instance['region']) : '';
    $postal_code = isset($instance['postal_code']) ? esc_attr($instance['postal_code']) : '';
    $tel = isset($instance['tel']) ? esc_attr($instance['tel']) : '';
    $email = isset($instance['email']) ? esc_attr($instance['email']) : '';
  ?>
    <p>
      <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php echo theme_locals("title"); ?></label>
      <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
    </p>
    <p>
      <label for="<?php echo esc_attr($this->get_field_id('street_address')); ?>"><?php echo theme_locals("street"); ?></label>
      <input class="widefat" id="<?php echo esc_attr($this->get_field_id('street_address')); ?>" name="<?php echo esc_attr($this->get_field_name('street_address')); ?>" type="text" value="<?php echo esc_attr($street_address); ?>" />
    </p>
    <p>
      <label for="<?php echo esc_attr($this->get_field_id('locality')); ?>"><?php echo theme_locals("city"); ?></label>
      <input class="widefat" id="<?php echo esc_attr($this->get_field_id('locality')); ?>" name="<?php echo esc_attr($this->get_field_name('locality')); ?>" type="text" value="<?php echo esc_attr($locality); ?>" />
    </p>
    <p>
      <label for="<?php echo esc_attr($this->get_field_id('region')); ?>"><?php echo theme_locals("state"); ?></label>
      <input class="widefat" id="<?php echo esc_attr($this->get_field_id('region')); ?>" name="<?php echo esc_attr($this->get_field_name('region')); ?>" type="text" value="<?php echo esc_attr($region); ?>" />
    </p>
    <p>
      <label for="<?php echo esc_attr($this->get_field_id('postal_code')); ?>"><?php echo theme_locals("zipcode"); ?></label>
      <input class="widefat" id="<?php echo esc_attr($this->get_field_id('postal_code')); ?>" name="<?php echo esc_attr($this->get_field_name('postal_code')); ?>" type="text" value="<?php echo esc_attr($postal_code); ?>" />
    </p>
    <p>
      <label for="<?php echo esc_attr($this->get_field_id('tel')); ?>"><?php echo theme_locals("telephone"); ?></label>
      <input class="widefat" id="<?php echo esc_attr($this->get_field_id('tel')); ?>" name="<?php echo esc_attr($this->get_field_name('tel')); ?>" type="text" value="<?php echo esc_attr($tel); ?>" />
    </p>
    <p>
      <label for="<?php echo esc_attr($this->get_field_id('email')); ?>"><?php echo theme_locals("email"); ?></label>
      <input class="widefat" id="<?php echo esc_attr($this->get_field_id('email')); ?>" name="<?php echo esc_attr($this->get_field_name('email')); ?>" type="text" value="<?php echo esc_attr($email); ?>" />
    </p>
  <?php
  }
}

function cherry_widget_init() {
  register_widget('MY_Vcard_Widget');
}

add_action('widgets_init', 'cherry_widget_init');