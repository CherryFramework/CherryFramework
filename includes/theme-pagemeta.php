<?php

// ==== Page Format meta boxes ====================================== //


// === Define Metabox Fields ====================================== //

$prefix = 'tz_';

$meta_box_category = array(
	'id' => 'tz-meta-box-category',
	'title' => 'category_include',
	'page' => 'page',
	'context' => 'normal',
	'priority' => 'high',
	'fields' => array(
		array( "name" => 'category_slug',
				"desc" => 'category_desc',
				"id" => $prefix."category_include",
				"type" => "text",
				"std" => ""
			),
	),
	
);

add_action('admin_menu', 'tz_add_box_page');
/*-----------------------------------------------------------------------------------*/
/*	Add metabox to edit page
/*-----------------------------------------------------------------------------------*/
 
function tz_add_box_page() {
	global $meta_box_category;
 
	add_meta_box($meta_box_category['id'], theme_locals($meta_box_category['title']), 'tz_show_box_category', $meta_box_category['page'], $meta_box_category['context'], $meta_box_category['priority']);
}


/*-----------------------------------------------------------------------------------*/
/*	Callback function to show fields in meta box
/*-----------------------------------------------------------------------------------*/
function tz_show_box_category() {
	global $meta_box_category, $post;

	// Use nonce for verification
	echo '<input type="hidden" name="tz_meta_box_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';
 
	echo '<table class="form-table">';
 
	foreach ($meta_box_category['fields'] as $field) {
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


add_action('save_post', 'tz_save_data_page');
/*-----------------------------------------------------------------------------------*/
/*	Save data when post is edited
/*-----------------------------------------------------------------------------------*/
 
function tz_save_data_page($post_id) {
	global $meta_box_category;
 
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
	
	foreach ($meta_box_category['fields'] as $field) {
		$old = get_post_meta($post_id, $field['id'], true);
		$new = $_POST[$field['id']];
 
		if ($new && $new != $old) {
			update_post_meta($post_id, $field['id'], stripslashes(htmlspecialchars($new)));
		} elseif ('' == $new && $old) {
			delete_post_meta($post_id, $field['id'], $old);
		}
	}
}