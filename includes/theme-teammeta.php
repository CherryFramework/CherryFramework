<?php
/*-----------------------------------------------------------------------------------
	Metaboxes for Team (Staff)
-----------------------------------------------------------------------------------*/

/*-----------------------------------------------------------------------------------*/
/*	Define Metabox Fields
/*-----------------------------------------------------------------------------------*/
$prefix = 'my_';
$meta_box_team = array(
	'id'       => 'my-meta-box-team',
	'title'    => "personal_options",
	'page'     => 'team',
	'context'  => 'normal',
	'priority' => 'high',
	'fields'   => array(
		array(
			'name' => "position",
			'desc' => "position_desc",
			'id'   => $prefix . 'team_pos',
			'type' => 'text',
			'std'  => ''
		),
		array(
			'name' => "team_email",
			'desc' => "team_email_desc",
			'id'   => $prefix . 'team_email',
			'type' => 'text',
			'std'  => ''
		),
		array(
			'name' => "info",
			'desc' => "info_desc_2",
			'id'   => $prefix . 'team_info',
			'type' => 'text',
			'std'  => ''
		)
	)
);
$team_networks = array(
	'id'       => 'team_networks',
	'title'    => "s_n",
	'page'     => 'team',
	'context'  => 'normal',
	'priority' => 'high'
);
/*-----------------------------------------------------------------------------------*/
/*	Add metabox to edit page
/*-----------------------------------------------------------------------------------*/
function my_add_box_team() {
	global $meta_box_team, $team_networks;

	add_meta_box($meta_box_team['id'], theme_locals($meta_box_team['title']), 'my_show_box_team', $meta_box_team['page'], $meta_box_team['context'], $meta_box_team['priority']);
	add_meta_box($team_networks['id'], theme_locals($team_networks['title']), 'my_social_networks', $team_networks['page'], $team_networks['context'], $team_networks['priority']);
}
add_action('admin_menu', 'my_add_box_team');

/*-----------------------------------------------------------------------------------*/
/*	Callback function to show fields in meta box
/*-----------------------------------------------------------------------------------*/
function my_show_box_team() {
	global $meta_box_team, $post;
	echo '<p style="padding:10px 0 0 0;">'.theme_locals("personal_options_desc").'</p>';
	// Use nonce for verification
	echo '<input type="hidden" name="my_meta_box_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';
	echo '<table class="form-table">';
	foreach ($meta_box_team['fields'] as $field) {
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
			//If textarea
			case 'textarea':
				echo '<tr style="border-top:1px solid #eeeeee;">',
					'<th style="width:25%"><label for="', $field['id'], '"><strong>', theme_locals($field['name']), '</strong><span style="line-height:18px; display:block; color:#999; margin:5px 0 0 0;">'. theme_locals($field['desc']).'</span></label></th>',
					'<td>';
				echo '<textarea name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : stripslashes(htmlspecialchars(( $field['std']), ENT_QUOTES)), '" rows="8" cols="5" style="width:100%; margin-right: 20px; float:left;">', $meta ? $meta : stripslashes(htmlspecialchars(( $field['std']), ENT_QUOTES)), '</textarea>';
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
function my_social_networks() {
	global $post;
	$post_id = $post->ID;
	$fields_id_value = get_option('fields_id_value'.$post_id, '');
	?>
	<p style="padding:10px 0 0 0;"><?php echo theme_locals('your_s_n') ?><br><em><?php echo theme_locals('icon_desc') ?></em></p>
	<input type="hidden" name="my_team_networks_nonce" value="<?php echo wp_create_nonce(basename(__FILE__)) ?>" />
	<input type="hidden" name="fields_id" value="<?php  echo $fields_id_value; ?>" />
	<input type="hidden" name="old_fields_id" value="<?php  echo $fields_id_value; ?>" />
	<table class="form-table">
		<tr style="border-top:1px solid #eeeeee;">
			<th style="width:25%">
				<label for="#network_title">
					<strong><?php echo theme_locals('network_title'); ?></strong>
					<span style=" display:block; color:#999; margin:5px 0 0 0; line-height: 18px;">'<?php echo theme_locals('network_title_desc'); ?></span>
				</label>
			</th>
			<td>
				<input id='network_title' type="text" name="network_title" value="<?php  echo get_post_meta($post_id, 'network_title', true); ?>" style="width:100%;"/>
			</td>
		</tr>
	</table>
	<table class="form-table" id='social_network'>
		<?php
			$fields_array = explode(" ", $fields_id_value);
			$item_count = $fields_array[0]=='' ? 0 : $fields_array[count($fields_array)-1] ;
			$title_added = false;
			if($item_count != 0){
				foreach ($fields_array as $field) {
					$network_array = explode(";", get_option('network_'.$post_id.'_'.$field, array('','','')));
						if($title_added == false){
							echo '<tr style="border-top:1px solid #eeeeee;" id="titles_social_network"><th style="width:15%"><strong>'.theme_locals("icon").'</strong></th><th style="width:20%"><strong>'.theme_locals("title").'</strong></th><th style="width:65%"><strong>'.theme_locals("page_url").'</strong></th><th style="width:5%"></th></tr>';
						}
						echo '<tr style="border-top:1px solid #eeeeee;"  id="network_'.$field.'"><th style="width:15%"><input type="text" name="network_icon_'.$field.'" id="network_icon_'.$field.'" value="'.$network_array[0].'"style="width:100%; margin-right: 20px; float:left;" /></th>';
						echo '<th style="width:20%"><input type="text" name="network_title_'.$field.'" id="network_title_'.$field.'" value="'.$network_array[1].'" style="width:100%; margin-right: 20px; float:left;" /></th>';
						echo '<th style="width:60%"><input type="text" name="network_url_'.$field.'" id="network_url_'.$field.'" value="'.$network_array[2].'" style="width:100%; margin-right: 20px; float:left;" /></th>';
						echo '<th style="width:5%"><a name="network_'.$field.'" class="button delete_network" href="#">'.theme_locals("delete").'</a></th></tr>';
						$title_added = true;
				}
			}
		?>
		<tr id="tr_add_network" style="border-top:1px solid #eeeeee; width:100%;">
			<th style="width:15%"><a id="add_network" class="button" href="#"><?php echo 'Add Social Network' ?></a></th>
			<th style="width:20%"></th>
			<th style="width:60%"></th>
			<th style="width:5%"></th>
		</tr>
	</table>
	<script>
		jQuery(function(){
			var add_item = parseInt('<?php echo $item_count ?>');

			jQuery('#add_network').click(function(){
				var html_item = (add_item == 0) ? '<tr style="border-top:1px solid #eeeeee;" id="titles_social_network"><th style="width:15%"><strong><?php echo theme_locals("icon") ?></strong></th><th style="width:20%"><strong><?php echo theme_locals("title") ?></strong></th><th style="width:65%"><strong><?php echo theme_locals("page_url") ?></strong></th><th style="width:5%"></th></tr>' : '',
					fields_id_value;
				++add_item;
				fields_id_value = jQuery('input[name="fields_id"]').val()+' '+add_item;
				html_item += '<tr style="border-top:1px solid #eeeeee;"  id="network_'+add_item+'"><th style="width:15%"><input type="text" name="network_icon_'+add_item+'" id="network_icon_'+add_item+'" style="width:100%; margin-right: 20px; float:left;" /></th>';
				html_item += '<th style="width:20%"><input type="text" name="network_title_'+add_item+'" id="network_title_'+add_item+'" style="width:100%; margin-right: 20px; float:left;" /></th>';
				html_item += '<th style="width:60%"><input type="text" name="network_url_'+add_item+'" id="network_url_'+add_item+'" style="width:100%; margin-right: 20px; float:left;" /></th>';
				html_item += '<th style="width:5%"><a name="network_'+add_item+'" class="button delete_network" href="#"><?php echo theme_locals("delete") ?></a></th></tr>';
				jQuery('#tr_add_network').before(html_item);

				jQuery('input[name="fields_id"]').val(fields_id_value);
				return !1;
			});
			jQuery('.delete_network').live('click', function(){
				var item_name = jQuery(this).attr('name'),
					fields_id_value_array = jQuery('input[name="fields_id"]').val().split(" "),
					delete_id = Number(item_name.replace(/\D+/g,""));

				jQuery(this).die('click');
				fields_id_value_array.splice(find(fields_id_value_array, delete_id), 1);
				jQuery('input[name="fields_id"]').val(fields_id_value_array.join(' '));
				if(jQuery('input[name="fields_id"]').val()==''){
					jQuery('#titles_social_network').remove();
					add_item = 0;
				}
				jQuery('#'+item_name).remove();
				return !1;
			});
			function find(array, value) {
				for(var i=0; i<array.length; i++) {
					if (array[i] == value) return i;
				}
				return -1;
			}

		});
	</script>
	<?php
}

/*-----------------------------------------------------------------------------------*/
/*	Save data when post is edited
/*-----------------------------------------------------------------------------------*/
function my_save_data_team($post_id) {
	global $meta_box_team;
	// verify nonce
	if (!isset($_POST['my_meta_box_nonce']) || !wp_verify_nonce($_POST['my_meta_box_nonce'], basename(__FILE__))) {
		return $post_id;
	}
	// check autosave
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return $post_id;
	}
	// check permissions
	if ('page' == $_POST['post_type']) {
		if (!current_user_can('edit_team', $post_id)) {
			return $post_id;
		}
	} elseif (!current_user_can('edit_post', $post_id)) {
		return $post_id;
	}
	foreach ($meta_box_team['fields'] as $field) {
		$old = get_post_meta($post_id, $field['id'], true);
		$new = $_POST[$field['id']];
		if ($new && $new != $old) {
			update_post_meta($post_id, $field['id'], stripslashes(htmlspecialchars($new)));
		} elseif ('' == $new && $old) {
			delete_post_meta($post_id, $field['id'], $old);
		}
	}
	$post_fields_id = trim($_POST['fields_id']) ;
	$old_fields_id = explode(" ", trim($_POST['old_fields_id']));
	$save_fields_array = explode(" ", $post_fields_id);
	$unite_fields_array = array_unique(array_merge($save_fields_array, $old_fields_id));
	$network_title = $_POST['network_title'];
	if($network_title){
		update_post_meta($post_id, 'network_title', stripslashes(htmlspecialchars($network_title)));
	}else{
		delete_post_meta($post_id, 'network_title');
	}
	if($post_fields_id){
		update_option('fields_id_value'.$post_id, $post_fields_id);
	}else{
		delete_option('fields_id_value'.$post_id, $post_fields_id);
	}
	foreach ($unite_fields_array as $fields_id) {
		if(in_array($fields_id, $save_fields_array)){
			if($fields_id){
				update_option('network_'.$post_id.'_'.$fields_id, $_POST['network_icon_'.$fields_id].';'.$_POST['network_title_'.$fields_id].';'.$_POST['network_url_'.$fields_id]);
			}
		}else{
			delete_option('network_'.$post_id.'_'.$fields_id);
		}
	}
}
add_action('save_post', 'my_save_data_team');