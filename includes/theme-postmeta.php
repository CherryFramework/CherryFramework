<?php
// === Define Metabox Fields ====================================== //

$prefix = 'tz_';

$meta_box_quote = array(
	'id'       => 'tz-meta-box-quote',
	'title'    => "quote_settings",
	'page'     => 'post',
	'context'  => 'normal',
	'priority' => 'high',
	'fields'   => array(
		array( "name"  => "quote",
				"desc" => "quote_desc",
				"id"   => $prefix."quote",
				"type" => "textarea",
				"std"  => ""
			),
		array( "name"  => "author",
				"desc" => "author_desc",
				"id"   => $prefix."author_quote",
				"type" => "text",
				"std"  => ""
			),
	),
);

$meta_box_link = array(
	'id'       => 'tz-meta-box-link',
	'title'    =>  "link_settings",
	'page'     => 'post',
	'context'  => 'normal',
	'priority' => 'high',
	'fields'   => array(
		array( "name"  => "the_url",
				"desc" => "the_url_desc",
				"id"   => $prefix."link_url",
				"type" => "text",
				"std"  => ""
			),
	),
);

$meta_box_image_label = array(
	''    => "",
	'yes' => "yes",
	'no'  => "no"
);

$meta_box_image = array(
	'id'       => 'tz-meta-box-image',
	'title'    =>  "image_settings",
	'page'     => 'post',
	'context'  => 'normal',
	'priority' => 'high',
	'fields'   => array(
		array( "name"     => "enable_lightbox",
				"desc"    => "enable_lightbox_desc",
				"id"      => $prefix."image_lightbox",
				"type"    => "select",
				'std'     => "",
				'options' => array('', 'yes', 'no'),
			),
	),
);

$meta_box_audio = array(
	'id'       => 'tz-meta-box-audio',
	'title'    =>  "audio_settings",
	'page'     => 'post',
	'context'  => 'normal',
	'priority' => 'high',
	'fields'   => array(
		array( "name"  => "title",
				"desc" => "audio_title_desc",
				"id"   => $prefix."audio_title",
				"type" => "text",
				"std"  => ""
			),
		array( "name"  => "artist",
				"desc" => "audio_artist_desc",
				"id"   => $prefix."audio_artist",
				"type" => "text",
				"std"  => ""
			),
		array( "name"     => "audio_format",
				"desc"    => "audio_format_desc",
				"id"      => $prefix."audio_format",
				"type"    => "select",
				"std"     => "",
				"options" => array('', 'mp3', 'wav', 'ogg')
			),
		array( "name"  => "audio_url",
				"desc" => "audio_url_desc",
				"id"   => $prefix."audio_url",
				"type" => "text",
				"std"  => ""
			)
	),
);

$meta_box_video = array(
	'id'       => 'tz-meta-box-video',
	'title'    =>  "video_settings",
	'page'     => 'post',
	'context'  => 'normal',
	'priority' => 'high',
	'fields'   => array(
		array( "name"  => "title",
				"desc" => "title_desc",
				"id"   => $prefix."video_title",
				"type" => "text",
				"std"  => ""
			),
		array( "name"  => "artist",
				"desc" => "artist_desc",
				"id"   => $prefix."video_artist",
				"type" => "text",
				"std"  => ""
			),
		array( "name"  => 'url_1',
				"desc" => "url_1_desc",
				"id"   => $prefix."m4v_url",
				"type" => "text",
				"std"  => ""
			),
		array( "name"  => 'url_2',
				"desc" => "url_2_desc",
				"id"   => $prefix."ogv_url",
				"type" => "text",
				"std"  => ""
			),
		array( "name"  => "embedded_code",
				"desc" => "embedded_code_desc",
				"id"   => $prefix."video_embed",
				"type" => "textarea",
				"std"  => ""
			)
		)
);


add_action('admin_menu', 'tz_add_box');
/*-----------------------------------------------------------------------------------*/
/*	Add metabox to edit page
/*-----------------------------------------------------------------------------------*/
function tz_add_box() {
	global $meta_box_quote, $meta_box_image, $meta_box_link, $meta_box_audio, $meta_box_video;

	add_meta_box($meta_box_quote['id'], theme_locals($meta_box_quote['title']), 'tz_show_box_quote', $meta_box_quote['page'], $meta_box_quote['context'], $meta_box_quote['priority']);
	add_meta_box($meta_box_image['id'], theme_locals($meta_box_image['title']), 'tz_show_box_image', $meta_box_image['page'], $meta_box_image['context'], $meta_box_image['priority']);
	add_meta_box($meta_box_link['id'], theme_locals($meta_box_link['title']), 'tz_show_box_link', $meta_box_link['page'], $meta_box_link['context'], $meta_box_link['priority']);
	add_meta_box($meta_box_audio['id'], theme_locals($meta_box_audio['title']), 'tz_show_box_audio', $meta_box_audio['page'], $meta_box_audio['context'], $meta_box_audio['priority']);
	add_meta_box($meta_box_video['id'], theme_locals($meta_box_video['title']), 'tz_show_box_video', $meta_box_video['page'], $meta_box_video['context'], $meta_box_video['priority']);
}


/*-----------------------------------------------------------------------------------*/
/*	Callback function to show fields in meta box
/*-----------------------------------------------------------------------------------*/
function tz_show_box_quote() {
	global $meta_box_quote, $post;

	// Use nonce for verification
	echo '<input type="hidden" name="tz_meta_box_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';

	echo '<table class="form-table">';

	foreach ($meta_box_quote['fields'] as $field) {
		// get current post meta data
		$meta = get_post_meta($post->ID, $field['id'], true);
		switch ($field['type']) {

			//If Text
			case 'text':

			echo '<tr>',
				'<th style="width:25%"><label for="', $field['id'], '"><strong>', theme_locals($field['name']), '</strong><span style=" display:block; color:#999; margin:5px 0 0 0; line-height: 18px;">'. theme_locals($field['desc']).'</span></label></th>',
				'<td>';
			echo '<input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : stripslashes(htmlspecialchars(( $field['std']), ENT_QUOTES)), '" size="30" style="width:75%; margin-right: 20px; float:left;" />';

			break;

			//If textarea
			case 'textarea':

			echo '<tr>',
				'<th style="width:25%"><label for="', $field['id'], '"><strong>', theme_locals($field['name']), '</strong><span style="line-height:18px; display:block; color:#999; margin:5px 0 0 0;">'. theme_locals($field['desc']).'</span></label></th>',
				'<td>';
			echo '<textarea name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : stripslashes(htmlspecialchars(( $field['std']), ENT_QUOTES)), '" rows="8" cols="5" style="width:75%; margin-right: 20px; float:left;">', $meta ? $meta : stripslashes(htmlspecialchars(( $field['std']), ENT_QUOTES)), '</textarea>';

			break;
		}
	}
	echo '</table>';
}

function tz_show_box_link() {
	global $meta_box_link, $post;

	// Use nonce for verification
	echo '<input type="hidden" name="tz_meta_box_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';

	echo '<table class="form-table">';

	foreach ($meta_box_link['fields'] as $field) {
		// get current post meta data
		$meta = get_post_meta($post->ID, $field['id'], true);
		switch ($field['type']) {

			
			//If Text
			case 'text':
			
			echo '<tr>',
				'<th style="width:25%"><label for="', $field['id'], '"><strong>', theme_locals($field['name']), '</strong><span style=" display:block; color:#999; margin:5px 0 0 0; line-height: 18px;">'. theme_locals($field['desc']).'</span></label></th>',
				'<td>';
			echo '<input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : stripslashes(htmlspecialchars(( $field['std']), ENT_QUOTES)), '" size="30" style="width:75%; margin-right: 20px; float:left;" />';
			
			break;

		}
	}
	echo '</table>';
}

function tz_show_box_audio() {
	global $meta_box_audio, $post;

	// Use nonce for verification
	echo '<input type="hidden" name="tz_meta_box_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';

	echo '<table class="form-table">';

	foreach ($meta_box_audio['fields'] as $field) {
		// get current post meta data
		$meta = get_post_meta($post->ID, $field['id'], true);

		switch ($field['type']) {

			//If Text
			case 'text':
			
			echo '<tr>',
				'<th style="width:25%"><label for="', $field['id'], '"><strong>', theme_locals($field['name']), '</strong><span style=" display:block; color:#999; margin:5px 0 0 0; line-height: 18px;">'. theme_locals($field['desc']).'</span></label></th>',
				'<td>';
			echo '<input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : stripslashes(htmlspecialchars(( $field['std']), ENT_QUOTES)), '" size="30" style="width:75%; margin-right: 20px; float:left;" />';
			
			break;
			
			//If Select
			case 'select':
			
				echo '<tr>',
				'<th style="width:25%"><label for="', $field['id'], '"><strong>', theme_locals($field['name']), '</strong><span style=" display:block; color:#999; margin:5px 0 0 0;">'. theme_locals($field['desc']).'</span></label></th>',
				'<td>';
			
				echo'<select name="'.$field['id'].'">';
			
				foreach ($field['options'] as $option) {
					
					echo'<option';
					if ($meta == $option ) { 
						echo ' selected="selected"'; 
					}
					echo'>'. $option .'</option>';
				
				}
				
				echo'</select>';

			break;
		}
	}
	echo '</table>';
}

function tz_show_box_video() {
	global $meta_box_video, $post;

	// Use nonce for verification
	echo '<input type="hidden" name="tz_meta_box_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';

	echo '<table class="form-table">';

	foreach ($meta_box_video['fields'] as $field) {
		// get current post meta data
		$meta = get_post_meta($post->ID, $field['id'], true);
		switch ($field['type']) {

			
			//If Text
			case 'text':
			
			echo '<tr>',
				'<th style="width:25%"><label for="', $field['id'], '"><strong>', theme_locals($field['name']), '</strong><span style=" display:block; color:#999; margin:5px 0 0 0; line-height: 18px;">'. theme_locals($field['desc']).'</span></label></th>',
				'<td>';
			echo '<input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : stripslashes(htmlspecialchars(( $field['std']), ENT_QUOTES)), '" size="30" style="width:75%; margin-right: 20px; float:left;" />';
			
			break;

			
			//If textarea
			case 'textarea':
			
			echo '<tr>',
				'<th style="width:25%"><label for="', $field['id'], '"><strong>', theme_locals($field['name']), '</strong><span style="line-height:18px; display:block; color:#999; margin:5px 0 0 0;">'. theme_locals($field['desc']).'</span></label></th>',
				'<td>';
			echo '<textarea name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : stripslashes(htmlspecialchars(( $field['std']), ENT_QUOTES)), '" rows="8" cols="5" style="width:75%; margin-right: 20px; float:left;">', $meta ? $meta : stripslashes(htmlspecialchars(( $field['std']), ENT_QUOTES)), '</textarea>';
			
			break;
			
			//If Select
			case 'select':
			
				echo '<tr>',
				'<th style="width:25%"><label for="', $field['id'], '"><strong>', theme_locals($field['name']), '</strong><span style=" display:block; color:#999; margin:5px 0 0 0;">'. theme_locals($field['desc']).'</span></label></th>',
				'<td>';
			
				echo'<select name="'.$field['id'].'">';
			
				foreach ($field['options'] as $option) {
					
					echo'<option';
					if ($meta == $option ) { 
						echo ' selected="selected"'; 
					}
					echo'>'. $option .'</option>';
				
				}
				
				echo'</select>';
			
			break;
		}
	}
	echo '</table>';
}

function tz_show_box_image() {
	global $meta_box_image, $post, $meta_box_image_label;

	// Use nonce for verification
	echo '<input type="hidden" name="tz_meta_box_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';

	echo '<table class="form-table">';

	foreach ($meta_box_image['fields'] as $field) {
		
		// get current post meta data
		$meta = get_post_meta($post->ID, $field['id'], true);
		
		switch ($field['type']) {

			//If Select
			case 'select':
			
				echo '<tr>',
				'<th style="width:25%"><label for="', $field['id'], '"><strong>', theme_locals($field['name']), '</strong><span style=" display:block; color:#999; margin:5px 0 0 0;">'. theme_locals($field['desc']).'</span></label></th>',
				'<td>';
			
				echo'<select name="'.$field['id'].'">';
			
				foreach ($field['options'] as $option) {
					
					echo'<option';
					if ($meta == $option ) { 
						echo ' selected="selected"'; 
					}
					echo' value="'.$option.'">'. theme_locals($meta_box_image_label[$option]) .'</option>';
				}
				
				echo'</select>';
			
			break;
		}
	}
	echo '</table>';
}


add_action('save_post', 'tz_save_data');
/*-----------------------------------------------------------------------------------*/
/*	Save data when post is edited
/*-----------------------------------------------------------------------------------*/
function tz_save_data($post_id) {
	global $meta_box_quote, $meta_box_link, $meta_box_image, $meta_box_audio, $meta_box_video;

	// verify nonce
	if (!isset($_POST['tz_meta_box_nonce']) || !wp_verify_nonce($_POST['tz_meta_box_nonce'], basename(__FILE__))) {
		return $post_id;
	}

	// check autosave
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return $post_id;
	}

	// check permissions
	if ('page' == $_POST['post_type']) {
		if (!current_user_can('edit_page', $post_id)) {
			return $post_id;
		}
	} elseif (!current_user_can('edit_post', $post_id)) {
		return $post_id;
	}

	foreach ($meta_box_quote['fields'] as $field) {
		$old = get_post_meta($post_id, $field['id'], true);
		$new = $_POST[$field['id']];

		if ($new && $new != $old) {
			update_post_meta($post_id, $field['id'], stripslashes(htmlspecialchars($new)));
		} elseif ('' == $new && $old) {
			delete_post_meta($post_id, $field['id'], $old);
		}
	}

	foreach ($meta_box_link['fields'] as $field) {
		$old = get_post_meta($post_id, $field['id'], true);
		$new = $_POST[$field['id']];

		if ($new && $new != $old) {
			update_post_meta($post_id, $field['id'], stripslashes(htmlspecialchars($new)));
		} elseif ('' == $new && $old) {
			delete_post_meta($post_id, $field['id'], $old);
		}
	}

	foreach ($meta_box_audio['fields'] as $field) {
		$old = get_post_meta($post_id, $field['id'], true);
		$new = $_POST[$field['id']];

		if ($new && $new != $old) {
			update_post_meta($post_id, $field['id'],stripslashes(htmlspecialchars($new)));
		} elseif ('' == $new && $old) {
			delete_post_meta($post_id, $field['id'], $old);
		}
	}

	foreach ($meta_box_video['fields'] as $field) {
		$old = get_post_meta($post_id, $field['id'], true);
		$new = $_POST[$field['id']];

		if ($new && $new != $old) {
			update_post_meta($post_id, $field['id'], stripslashes(htmlspecialchars($new)));
		} elseif ('' == $new && $old) {
			delete_post_meta($post_id, $field['id'], $old);
		}
	}

	foreach ($meta_box_image['fields'] as $field) {
		$old = get_post_meta($post_id, $field['id'], true);
		$new = $_POST[$field['id']];

		if ($new && $new != $old) {
			update_post_meta($post_id, $field['id'], stripslashes(htmlspecialchars($new)));
		} elseif ('' == $new && $old) {
			delete_post_meta($post_id, $field['id'], $old);
		}
	}
}