<?php
	include_once (PARENT_DIR . '/admin/data_management/update.php');

	@define('PARENT_NAME', 'CherryFramework');

	$framework_version = get_theme_info(PARENT_NAME, 'Version'); 
	function get_theme_info($theme_name, $data_type=''){
		if ( function_exists('wp_get_theme') ) {
			$theme = wp_get_theme($theme_name);
			$return_info = $data_type ? $theme -> $data_type : $theme ;
		} else {
			$theme = get_theme_data(get_theme_root() . '/' . $theme_name . '/style.css');
			$return_info = $data_type ? $theme[$data_type] : $theme ;
		}
		return $return_info;
	}

	function get_file_date($theme_name){
		$get_date_backup = get_option($theme_name."_date_backup");
		$get_version_backup = get_option($theme_name."_version_backup");
		$theme = new stdClass();

		if($get_date_backup!=''){
			$theme->date = $get_date_backup;
		}else{
			$theme->date = theme_locals("no_backup");
		}
		if($get_version_backup!=''){
			$theme->backup_version = $get_version_backup;
		}else{
			$theme->backup_version = theme_locals("no_backup");
		}
		return $theme;
	}

	function add_radio_button($theme_name, $input_name, $checked=false){
		$input_radio = '<input ';
		$input_radio .= 'class="theme_name ';
		if(get_option($theme_name."_date_backup")==""){
			$input_radio .= 'no_backup';
		}
		$input_radio .= '" type="radio" value="'.$theme_name.'"';
		if($input_name!=""){
			$input_radio .= ' name="'.$input_name.'" ';
		}
		if($checked){
			$input_radio .= 'checked ';
		}
		$input_radio .= '>';
		echo $input_radio;
	}

	function check_update(){	
		global $wp_version, $framework_version, $framework_update;
		$theme_base = get_theme_info(PARENT_NAME, 'Template');
		$response["new_version"] = $framework_version;
		$request = array('slug' => $theme_base, 'version' => $framework_version);
		$send_for_check = array( 'body' => array('action' => 'theme_update', 'request' => serialize($request), 'api-key' => md5(get_bloginfo('url'))), 'user-agent' => 'WordPress/' . $wp_version . '; ' . get_bloginfo('url'));
		$raw_response = wp_remote_post(API_URL, $send_for_check);
		if (!is_wp_error($raw_response) && ($raw_response['response']['code'] == 200)){
			$response= unserialize($raw_response['body']);
		}
		if(!isset($response)){
			if($response["new_version"]==""){
				$response["new_version"] = $framework_version;
			}
		}
		return $response["new_version"];
	}


	if ( ! function_exists( 'admin_data_management' ) ) {
		function admin_data_management () {
			global $framework_version;
			?>
			<div id="optionsframework-metabox" class="metabox-holder">
				<div id="optionsframework" class="postbox store-holder">
					<div class="wrap">
						<h3><?php echo theme_locals("data_management"); ?></h3>
						<div class="data_management">
							<p><?php echo theme_locals("info_box_1"); ?></p>
							<div class="theme_box">
								<h4><?php echo theme_locals('cherry_framework'); ?></h4>
								<?php if(FILE_WRITEABLE){ ?>
									<div class="error"><p><?php echo theme_locals("info_box_2"); ?></p></div>
								<?php } ?>
								<div class="controls framework_info">
									<span class="data_label"><?php echo theme_locals("name"); ?>:</span><span class="data_val"><?php echo get_theme_info(PARENT_NAME, 'Name'); ?></span><br>
									<span class="data_label"><?php echo theme_locals("author"); ?>:</span><span class="data_val"><?php echo get_theme_info(PARENT_NAME, 'Author'); ?></span><br>
									<span class="data_label"><?php echo theme_locals("your_version"); ?>:</span><span id="your_version_<?php echo PARENT_NAME; ?>" class="data_val"><?php echo $framework_version; ?></span><br>
									<span class="data_label"><?php echo theme_locals("update_version"); ?>:</span><span id="update_version" class="data_val"><?php echo check_update(); ?></span><br>
									<span class="data_label"><?php echo theme_locals("backup_version"); ?>:</span><span id="version_<?php echo PARENT_NAME; ?>" class="data_val"><?php echo get_file_date(PARENT_NAME)->backup_version ?></span><br>
									<span class="data_label"><?php echo theme_locals("backup_date"); ?>:</span><span id="date_<?php echo PARENT_NAME; ?>" class="data_val"><?php echo get_file_date(PARENT_NAME)->date ?></span><br>
									<span class="data_label"><?php echo theme_locals("description"); ?>:</span><span class="data_val"><?php echo get_theme_info(PARENT_NAME, 'Description'); ?></span><br>
									<?php add_radio_button(get_theme_info(PARENT_NAME, 'Template'), "", true); ?>
								</div>
								<?php if ( FILE_WRITEABLE ) { ?>
								<div class="buttons_controls">
									<div class="button_wrapper">
										<?php 
											$update_url = wp_nonce_url('update.php?action=upgrade-theme&amp;theme=' . urlencode(PARENT_NAME), 'upgrade-theme_'.urlencode(PARENT_NAME));
											$disable_class= "";
											$cap= "";
											if($framework_version>=check_update()){
												$cap = '<span class="cap"></span>';
												$disable_class = "disable_button";
											}
											echo "<a id=\"update_framework\" class=\"button-primary ".$disable_class."\" href=\"".$update_url."\" onclick=\"if ( confirm('Updating this theme will lose any customizations you have made. \'Cancel\' to stop, \'OK\' to update.') ) {return true;}return false;\">".theme_locals("update")."</a>".$cap;
											
										?>
									</div>
									<div class="button_wrapper">
										<a class="button-primary backup_theme" href="<?php echo PARENT_NAME; ?>"  title="<?php echo theme_locals('backup'); ?>"><?php echo theme_locals("backup"); ?></a>
									</div>
									<div class="button_wrapper">
										<a class="button-primary restore_theme " href="<?php echo PARENT_NAME; ?>" title="<?php echo theme_locals('restore'); ?>"><?php echo theme_locals("restore"); ?></a>
									</div>
									<div class="button_wrapper">
										<a class="button-primary download_backup" href="<?php echo PARENT_NAME; ?>" title="<?php echo theme_locals('download_backup'); ?>"><?php echo theme_locals("download_backup"); ?></a>
									</div>
								</div>
								<?php }else{
									printf('<p><em>'.theme_locals('warning_notice_1').' '.theme_locals('warning_notice_3').'</em></p>');
								}?>
							</div>
							<?php
								$themes_dir = get_theme_root();
								$themes = scandir($themes_dir);
								$themes_array = array();
								foreach ($themes as $theme) {
									if(is_dir("$themes_dir/$theme")){
										if(strtolower(get_theme_info($theme, 'Template')) == 'cherryframework' && strtolower($theme) != 'cherryframework'){
											array_push($themes_array, $theme);
										}
									}
								}
								if(count($themes_array)!=0){
							?>

							<div class="theme_box">
								<h4><?php echo theme_locals("child_theme"); ?></h4>
								<div class="controls child_theme">
									<div class="child_theme_title">
										<span class="select"> </span>
										<span class="child_preview"><?php echo theme_locals("preview"); ?></span>
										<span class="name"><?php echo theme_locals("name"); ?></span>
										<span class="date"><?php echo theme_locals("backup_date"); ?></span>
									</div>
									<div class="child_theme_list">
										<?php
											$input_checked = true;
											foreach ($themes_array as $theme) {
												echo '<label>';
												echo '<span class="select">';
												add_radio_button($theme, "theme_name", $input_checked);
												if($input_checked){
													$input_checked = false;
												}
												echo '</span>';
												echo '<span class="child_preview">';
												if(file_exists($themes_dir."/$theme/screenshot.png")){
													echo '<img src="'.get_theme_root_uri()."/$theme/screenshot.png".'" alt="'.$theme.'">';
												}
												echo '</span>';
												echo '<span class="name">'.$theme.'</span>';
												echo '<span id="date_'.$theme.'" class="date">'.get_file_date($theme)->date.'</span>';
												echo '</label>';
												$not_child_theme = false;
											}
										?>
									</div>
								</div>
								<?php if (FILE_WRITEABLE) { ?>
								<div class="buttons_controls">
									<div class="button_wrapper">
										<a class="button-primary backup_theme" href="CherryFramework"  title="<?php echo theme_locals('backup'); ?>"><?php echo theme_locals("backup"); ?></a>
									</div>
									<div class="button_wrapper">
										<a class="button-primary restore_theme" href="CherryFramework" title="<?php echo theme_locals('restore'); ?>"><?php echo theme_locals("restore"); ?></a>
									</div>
									<div class="button_wrapper">
										<a class="button-primary download_backup" href="CherryFramework" title="<?php echo theme_locals('download_backup'); ?>"><?php echo theme_locals("download_backup"); ?></a>
									</div>
								</div>
								<?php }else{
									printf('<p><em>'.theme_locals('warning_notice_1').' '.theme_locals('warning_notice_3').'</em></p>');
								}?>
							</div>
							<?php
								}
							?>
						</div>
					</div>
				</div>
			</div>
			<?php
		}
	}
?>
