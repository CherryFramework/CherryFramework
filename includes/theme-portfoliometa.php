<?php

/*-----------------------------------------------------------------------------------

	Add image upload metaboxes to Portfolio items

-----------------------------------------------------------------------------------*/


/*-----------------------------------------------------------------------------------*/
/*	Define Metabox Fields
/*-----------------------------------------------------------------------------------*/

$prefix = 'tz_';
$meta_box_portfolio_type_label = array(
	"Image" => "image",
	"Slideshow" => "slideshow",
	"Grid Gallery" => "grid_gallery",
	"Video" => "video",
	"Audio" => "audio"
);
 
$meta_box_portfolio = array(
	'id' => 'tz-meta-box-portfolio',
	'title' => "portfolio_options",
	'page' => 'portfolio',
	'context' => 'normal',
	'priority' => 'high',
	'fields' => array(
    	array(
			'name' => "format",
			'desc' => "format_desc",
			'id' => $prefix . 'portfolio_type',
			"type" => "select",
			'std' => 'Image',
			'options' => array("Image", "Slideshow", "Grid Gallery", "Video", "Audio"),
		),
    	array(
    	   'name' => "date",
    	   'desc' => "date_desc",
    	   'id' => $prefix . 'portfolio_date',
    	   'type' => 'text',
    	   'std' => ''
    	),
    	array(
    	   'name' => "client",
    	   'desc' => "client_desc",
    	   'id' => $prefix . 'portfolio_client',
    	   'type' => 'text',
    	   'std' => ''
    	),
		array(
    	   'name' => "info",
    	   'desc' => "info_desc",
    	   'id' => $prefix . 'portfolio_info',
    	   'type' => 'text',
    	   'std' => ''
    	),
    	array(
    	   'name' => 'url',
    	   'desc' => "url_desc",
    	   'id' => $prefix . 'portfolio_url',
    	   'type' => 'text',
    	   'std' => ''
    	)
	)
);
// portfolio Lightbox
$meta_box_portfolio_lightbox = array(
	"no" => "no",
	"yes" => "yes"
);
$meta_box_portfolio_image = array(
	'id' => 'tz-meta-box-portfolio-image',
	'title' => "image_settings",
	'page' => 'portfolio',
	'context' => 'normal',
	'priority' => 'high',
	'fields' => array(
		array( "name" => "enable_lightbox",
				"desc" => "enable_lightbox_desc",
				"id" => $prefix."image_lightbox",
				"type" => "select",
				'std' => 'no',
				'options' => array("no", "yes")
			),
	),	
);


$meta_box_portfolio_video = array(
	'id' => 'tz-meta-box-portfolio-video',
	'title' => "video_settings",
	'page' => 'portfolio',
	'context' => 'normal',
	'priority' => 'high',
	'fields' => array(
		array( "name" => "title",
				"desc" => "video_settings_desc",
				"id" => $prefix."video_title",
				"type" => "text",
    	   		"std" => ""
			),
		array( "name" => "artist",
				"desc" => "artist_desc",
				"id" => $prefix."video_artist",
				"type" => "text",
    	   		"std" => ""
			),
		array( "name" => 'url_1',
				"desc" => "url_1_desc",
				"id" => $prefix."m4v_url",
				"type" => "text",
    	   		"std" => ""
			),
		array( "name" => 'url_2',
				"desc" => "url_2_desc",
				"id" => $prefix."ogv_url",
				"type" => "text",
    	   		"std" => ""
			),
		array( "name" => "embedded_code",
				"desc" => "embedded_code_desc",
				"id" => $prefix."video_embed",
				"type" => "textarea",
    	   		"std" => ""
			)
	),
	
);

$meta_box_portfolio_audio = array(
	'id' => 'tz-meta-box-portfolio-audio',
	'title' =>  "audio_settings",
	'page' => 'portfolio',
	'context' => 'normal',
	'priority' => 'high',
	'fields' => array(
		array( "name" => "title",
				"desc" => "audio_title_desc",
				"id" => $prefix."audio_title",
				"type" => "text",
    	   		"std" => ""
			),
		array( "name" => "artist",
				"desc" => "audio_artist_desc",
				"id" => $prefix."audio_artist",
				"type" => "text",
    	   		"std" => ""
			),
		array( "name" => "audio_format",
				"desc" => "audio_format_desc",
				"id" => $prefix."audio_format",
				"type" => "select",
				"std" => "mp3",
				"options" => array('mp3', 'wav', 'ogg')
			),
		array( "name" => "audio_url",
				"desc" => "audio_url_desc",
				"id" => $prefix."audio_url",
				"type" => "text",
    	   		"std" => ""
			)
	)
);

add_action('admin_menu', 'tz_add_box_portfolio');


/*-----------------------------------------------------------------------------------*/
/*	Add metabox to edit page
/*-----------------------------------------------------------------------------------*/
 
function tz_add_box_portfolio() {
	global $meta_box_portfolio, $meta_box_portfolio_image, $meta_box_portfolio_video, $meta_box_portfolio_audio;
	
	add_meta_box($meta_box_portfolio['id'], theme_locals($meta_box_portfolio['title']), 'tz_show_box_portfolio', $meta_box_portfolio['page'], $meta_box_portfolio['context'], $meta_box_portfolio['priority']);

	add_meta_box($meta_box_portfolio_image['id'], theme_locals($meta_box_portfolio_image['title']), 'tz_show_box_portfolio_image', $meta_box_portfolio_image['page'], $meta_box_portfolio_image['context'], $meta_box_portfolio_image['priority']);

	add_meta_box($meta_box_portfolio_video['id'], theme_locals($meta_box_portfolio_video['title']), 'tz_show_box_portfolio_video', $meta_box_portfolio_video['page'], $meta_box_portfolio_video['context'], $meta_box_portfolio_video['priority']);
	
	add_meta_box($meta_box_portfolio_audio['id'], theme_locals($meta_box_portfolio_audio['title']), 'tz_show_box_portfolio_audio', $meta_box_portfolio_audio['page'], $meta_box_portfolio_audio['context'], $meta_box_portfolio_audio['priority']);

}


/*-----------------------------------------------------------------------------------*/
/*	Callback function to show fields in meta box
/*-----------------------------------------------------------------------------------*/

function tz_show_box_portfolio() {
	global $meta_box_portfolio, $post, $meta_box_portfolio_type_label;
 	
	echo '<p style="padding:10px 0 0 0;">'.theme_locals("portfolio_format").'</p>';
	// Use nonce for verification
	echo '<input type="hidden" name="tz_meta_box_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';
 
	echo '<table class="form-table">';
 
	foreach ($meta_box_portfolio['fields'] as $field) {
		// get current post meta data
		$meta = get_post_meta($post->ID, $field['id'], true);
		switch ($field['type']) {
 
			
			//If Text		
			case 'text':
			
			echo '<tr style="border-top:1px solid #eeeeee;">',
				'<th style="width:25%"><label for="', $field['id'], '"><strong>', theme_locals($field['name']), '</strong><span style=" display:block; color:#999; margin:5px 0 0 0; line-height: 18px;">'. theme_locals($field['desc']).'</span></label></th>',
				'<td>';
			echo '<input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : stripslashes(htmlspecialchars(( $field['std']), ENT_QUOTES)), '" size="30" style="width:75%; margin-right: 20px; float:left;" />';
			
			break;
			
			//If Select	
			case 'select':
			
				echo '<tr>',
				'<th style="width:25%"><label for="', $field['id'], '"><strong>', theme_locals($field['name']), '</strong><span style=" display:block; color:#999; margin:5px 0 0 0; line-height: 18px;">'. theme_locals($field['desc']).'</span></label></th>',
				'<td>';
			
				echo'<select id="' . $field['id'] . '" name="'.$field['id'].'">';
			
				foreach ($field['options'] as $option) {
					
					echo'<option';
					if ($meta == $option ) { 
						echo ' selected="selected"'; 
					}
					echo' value="'.$option.'">'. theme_locals($meta_box_portfolio_type_label[$option]) .'</option>';
				
				} 
				
				echo'</select>';
			
			break; 
			
		}

	}
 
	echo '</table>';
}

function tz_show_box_portfolio_image() {
	global $meta_box_portfolio_image, $post, $meta_box_portfolio_lightbox;
 	
	// Use nonce for verification
	echo '<input type="hidden" name="tz_meta_box_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';
 
	echo '<table class="form-table">';
 
	foreach ($meta_box_portfolio_image['fields'] as $field) {
		// get current post meta data
		$meta = get_post_meta($post->ID, $field['id'], true);
		switch ($field['type']) {
 
			
			//If Text		
			case 'text':
			
			echo '<tr style="border-top:1px solid #eeeeee;">',
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
					echo ' value="'.$option.'">'. theme_locals($meta_box_portfolio_lightbox[$option]) .'</option>';
				
				} 
				
				echo'</select>';
			
			break;
		}

	}
 
	echo '</table>';
}

function tz_show_box_portfolio_video() {
	global $meta_box_portfolio_video, $post;
 	
	// Use nonce for verification
	echo '<input type="hidden" name="tz_meta_box_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';
 
	echo '<table class="form-table">';
 
	foreach ($meta_box_portfolio_video['fields'] as $field) {
		// get current post meta data
		$meta = get_post_meta($post->ID, $field['id'], true);
		switch ($field['type']) {
 
			
			//If Text		
			case 'text':
			
			echo '<tr>',
				'<th style="width:25%"><label for="', $field['id'], '"><strong>', theme_locals($field['name']), '</strong><span style="line-height:20px; display:block; color:#999; margin:5px 0 0 0;">'. theme_locals($field['desc']).'</span></label></th>',
				'<td>';
			echo '<input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : $field['std'],'" size="30" style="width:75%; margin-right: 20px; float:left;" />';
			
			break;
			
			//If textarea		
			case 'textarea':
			
			echo '<tr>',
				'<th style="width:25%"><label for="', $field['id'], '"><strong>', theme_locals($field['name']), '</strong><span style="line-height:18px; display:block; color:#999; margin:5px 0 0 0;">'. theme_locals($field['desc']).'</span></label></th>',
				'<td>';
			echo '<textarea name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : $field['std'], '" rows="8" cols="5" style="width:75%; margin-right: 20px; float:left;">', $meta ? $meta : $field['std'], '</textarea>';
			
			break;
			
			//If Select	
			case 'select':
			
				echo '<tr>',
				'<th style="width:25%"><label for="', $field['id'], '"><strong>', theme_locals($field['name']), '</strong><span style=" display:block; color:#999; margin:5px 0 0 0; line-height: 18px;">'. theme_locals($field['desc']).'</span></label></th>',
				'<td>';
			
				echo'<select id="' . $field['id'] . '" name="'.$field['id'].'">';
			
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

function tz_show_box_portfolio_audio() {
	global $meta_box_portfolio_audio, $post;
 	
	// Use nonce for verification
	echo '<input type="hidden" name="tz_meta_box_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';
 
	echo '<table class="form-table">';
 
	foreach ($meta_box_portfolio_audio['fields'] as $field) {
		// get current post meta data
		$meta = get_post_meta($post->ID, $field['id'], true);
		switch ($field['type']) {
 
			
			//If Text		
			case 'text':
			
			echo '<tr>',
				'<th style="width:25%"><label for="', $field['id'], '"><strong>', theme_locals($field['name']), '</strong><span style="line-height:20px; display:block; color:#999; margin:5px 0 0 0;">'. theme_locals($field['desc']).'</span></label></th>',
				'<td>';
			echo '<input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : $field['std'],'" size="30" style="width:75%; margin-right: 20px; float:left;" />';
			
			break;
			
			//If textarea		
			case 'textarea':
			
			echo '<tr style="border-top:1px solid #eeeeee;">',
				'<th style="width:25%"><label for="', $field['id'], '"><strong>', theme_locals($field['name']), '</strong><span style="line-height:18px; display:block; color:#999; margin:5px 0 0 0;">'. theme_locals($field['desc']).'</span></label></th>',
				'<td>';
			echo '<textarea name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : $field['std'], '" rows="8" cols="5" style="width:75%; margin-right: 20px; float:left;">', $meta ? $meta : $field['std'], '</textarea>';
			
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

 
add_action('save_post', 'tz_save_data_portfolio');


/*-----------------------------------------------------------------------------------*/
/*	Save data when post is edited
/*-----------------------------------------------------------------------------------*/
 
function tz_save_data_portfolio($post_id) {
	global $meta_box_portfolio, $meta_box_portfolio_video, $meta_box_portfolio_audio, $meta_box_portfolio_image;
 
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
 
	foreach ($meta_box_portfolio['fields'] as $field) {
		$old = get_post_meta($post_id, $field['id'], true);
		$new = $_POST[$field['id']];
 
		if ($new && $new != $old) {
			update_post_meta($post_id, $field['id'], stripslashes(htmlspecialchars($new)));
		} elseif ('' == $new && $old) {
			delete_post_meta($post_id, $field['id'], $old);
		}
	}

	foreach ($meta_box_portfolio_image['fields'] as $field) {
		$old = get_post_meta($post_id, $field['id'], true);
		$new = $_POST[$field['id']];
 
		if ($new && $new != $old) {
			update_post_meta($post_id, $field['id'], stripslashes(htmlspecialchars($new)));
		} elseif ('' == $new && $old) {
			delete_post_meta($post_id, $field['id'], $old);
		}
	}
	
	foreach ($meta_box_portfolio_video['fields'] as $field) {
		$old = get_post_meta($post_id, $field['id'], true);
		$new = $_POST[$field['id']];
 
		if ($new && $new != $old) {
			update_post_meta($post_id, $field['id'], stripslashes(htmlspecialchars($new)));
		} elseif ('' == $new && $old) {
			delete_post_meta($post_id, $field['id'], $old);
		}
	}
	
	foreach ($meta_box_portfolio_audio['fields'] as $field) {
		$old = get_post_meta($post_id, $field['id'], true);
		$new = $_POST[$field['id']];
 
		if ($new && $new != $old) {
			update_post_meta($post_id, $field['id'], stripslashes(htmlspecialchars($new)));
		} elseif ('' == $new && $old) {
			delete_post_meta($post_id, $field['id'], $old);
		}
	}
	
}