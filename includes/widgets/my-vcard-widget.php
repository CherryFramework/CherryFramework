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
	$instance['street_address'] = isset($instance['street_address']) ? $instance['street_address'] : ''; 
	$instance['locality'] = isset($instance['locality']) ? $instance['locality'] : '';
	$instance['region'] = isset($instance['region']) ? $instance['region'] : '';
	$instance['postal_code'] = isset($instance['postal_code']) ? $instance['postal_code'] : '';
	$instance['tel'] = isset($instance['tel']) ? $instance['tel'] : '';
	$instance['email'] = isset($instance['email']) ? $instance['email'] : '';
	$instance['gmap_disable'] = isset($instance['gmap_disable']) ? $instance['gmap_disable'] : '';
	$instance['gmap_html'] = isset($instance['gmap_html']) ? $instance['gmap_html'] : '';
	$meta_format = isset($instance['meta_format']) ? $instance['meta_format'] : 'none';

	if($meta_format=="icons"){
	  $street_address_format = '<i class="icon-home"></i>';
	  $locality_format = '<i class="icon-map-marker"></i>';
	  $region_format = '<i class="icon-globe"></i>';
	  $postal_code_format = '<i class="icon-file-text-alt"></i>';
	  $tel_format = '<i class="icon-phone"></i>';
	  $email_format = '<i class="icon-envelope-alt"></i>';
	} else  if($meta_format=="labels"){
	  $street_address_format = '<span class="ladle">'.theme_locals("street").' </span> ';
	  $locality_format = '<span class="ladle">'.theme_locals("city").' </span> ';
	  $region_format = '<span class="ladle">'.theme_locals("state").' </span> ';
	  $postal_code_format = '<span class="ladle">'.theme_locals("zipcode").' </span> ';
	  $tel_format = '<span class="ladle">'.theme_locals("telephone").' </span> ';
	  $email_format = '<span class="ladle">'.theme_locals("email").' </span> ';
	} else {
	  $street_address_format = '';
	  $locality_format = '';
	  $region_format = '';
	  $postal_code_format = '';
	  $tel_format = '';
	  $email_format = '';
	}


	echo $before_widget;
	if ($title) {
	  echo $before_title;
	  echo $title;
	  echo $after_title;
	}
  ?>
	<address class="vcard">
	  <?php if($instance['gmap_disable']=='on' && $instance['gmap_html']!=''){ ?>
		<div class="google-map" style="width:100%; overflow: hidden;"><?php echo $instance['gmap_html']; ?></div>
	  <?php }; ?>
	  <strong class="adr">
		<?php if($instance['street_address']!=''){ ?>
		  <div class="meta_format"><?php echo $street_address_format; ?><span class="street-address"><?php echo $instance['street_address']; ?></span></div>
		<?php }; 
		if($instance['locality']!=''){ ?>
		  <div class="meta_format"><?php echo $locality_format; ?><span class="locality"><?php echo $instance['locality']; ?></span></div>  
		<?php };
		if($instance['region']!=''){ ?>
		 <div class="meta_format"><?php echo $region_format; ?> <span class="region"><?php echo $instance['region']; ?></span></div>
		<?php };
		if($instance['postal_code']!=''){ ?>
		  <div class="meta_format"><?php echo $postal_code_format; ?><span class="postal-code"><?php echo $instance['postal_code']; ?></span></div>
		<?php }; ?>
	  </strong>
	  <?php if($instance['tel']!=''){ ?>
		<div class="meta_format"><?php echo $tel_format; ?><span class="tel"><span class="value"><a href="tel:<?php echo $instance['tel']; ?>"><?php echo $instance['tel']; ?></a></span></span></div>
	  <?php };
		if($instance['email']!=''){ ?>
		<div class="meta_format"><?php echo $email_format; ?><a class="email" href="mailto:<?php echo $instance['email']; ?>"><?php echo $instance['email']; ?></a></div>
	  <?php }; ?>
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
	$instance['gmap_disable'] = $new_instance['gmap_disable'];
	$instance['gmap_html'] = $new_instance['gmap_html'];
	$instance['gmap_width'] = $new_instance['gmap_width'];
	$instance['gmap_height'] = $new_instance['gmap_height'];
	$instance['meta_format'] = $new_instance['meta_format'];

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
	$defaults = array( 'title' => 'My Info', 'gmap_disable' => 'on', 'meta_format' => 'none');
	$instance = wp_parse_args( (array) $instance, $defaults );

	$title = isset($instance['title']) ? esc_attr($instance['title']) : '';
	$street_address = isset($instance['street_address']) ? esc_attr($instance['street_address']) : '';
	$locality = isset($instance['locality']) ? esc_attr($instance['locality']) : '';
	$region = isset($instance['region']) ? esc_attr($instance['region']) : '';
	$postal_code = isset($instance['postal_code']) ? esc_attr($instance['postal_code']) : '';
	$tel = isset($instance['tel']) ? esc_attr($instance['tel']) : '';
	$email = isset($instance['email']) ? esc_attr($instance['email']) : '';
	$gmap_disable = isset($instance['gmap_disable']) ? true : false;
	$gmap_html = isset($instance['gmap_html']) ? esc_attr($instance['gmap_html']) : '';
	$meta_format = isset($instance['meta_format']) ? esc_attr($instance['meta_format']) : "none";
  ?>
	<p>
	  <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php echo theme_locals("title"); ?></label>
	  <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
	</p>
	<p>
	  <fieldset style="padding:5px; border:1px solid #dfdfdf">
		  <legend style="margin:0 5px;"><?php echo theme_locals("map"); ?></legend>
		<p>
		  <input class="checkbox" id="<?php echo $this->get_field_id('gmap_disable'); ?>" name="<?php echo $this->get_field_name('gmap_disable'); ?>" type="checkbox" <?php checked($instance['gmap_disable'], 'on' ); ?> /> <label for="<?php echo $this->get_field_id('gmap_disable'); ?>"><?php echo theme_locals("gmap_disable"); ?></label>
		</p>
		<p>
		  <label for="<?php echo esc_attr($this->get_field_id('gmap_html')); ?>"><?php echo theme_locals("map_url"); ?></label>
		  <input class="widefat" id="<?php echo esc_attr($this->get_field_id('gmap_html')); ?>" name="<?php echo esc_attr($this->get_field_name('gmap_html')); ?>" type="text" value="<?php echo esc_attr($gmap_html); ?>" />
		</p>
	  </fieldset>
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
	<p>
	  <label for="<?php echo $this->get_field_id('meta_format'); ?>"><?php echo theme_locals("meta_format").":"; ?> 
		<select id="<?php echo $this->get_field_id('meta_format'); ?>" name="<?php echo $this->get_field_name('meta_format'); ?>" style="width:140px;" > 
		  <option value="none" <?php echo ($meta_format === 'none' ? ' selected="selected"' : ''); ?>><?php echo theme_locals("none") ?></option>
		  <option value="icons" <?php echo ($meta_format === 'icons' ? ' selected="selected"' : ''); ?>><?php echo theme_locals("icons") ?></option>
		  <option value="labels" <?php echo ($meta_format === 'labels' ? ' selected="selected"' : ''); ?>><?php echo theme_locals("labels") ?></option>
		</select>
	  </label>
	</p>
<?php
	}
}