<?php 
	include_once (PARENT_DIR . '/admin/seo/sitemap-generator.php');
	include_once (PARENT_DIR . '/admin/seo/seo-function.php');

	if (!function_exists('seo_settings_page')) {
		function seo_settings_page () { ?>
			<div id="optionsframework-wrap" class="wrap">
				<div id="icon-generic" class="icon32"><br></div><h2><?php echo theme_locals("seo"); ?></h2>
				<h2 class="nav-tab-wrapper">
					<a class="nav-tab" title="General settings" href="#general"><?php echo theme_locals("general"); ?></a>
					<a class="nav-tab" title="Settings sitemap XML" href="#sitemap-xml"><?php echo theme_locals("sitemap_xml"); ?></a>
				</h2>
				<div id="optionsframework-metabox">
					<div id="optionsframework" class="postbox store-holder">
						<form id='options'>
							<div id="general" class="group">
								<h3><?php echo theme_locals("general"); ?></h3>
								<div class="section">
									<h4 class="heading"><?php echo theme_locals("index_settings"); ?></h4>
									<div class="option clearfix">
										<div class="controls">
										<?php
											$index_settings = array(
												'generate_robots' => array('title' => theme_locals("generate_robots"), 'checked' => true),
												'admin_index' => array('title' => theme_locals("admin_index"), 'checked' => true),
												'plagin_index' => array('title' => theme_locals("plagin_index"), 'checked' => true),
												'theme_index' => array('title' => theme_locals("theme_index"), 'checked' => true),
												'media_index' => array('title' => theme_locals("media_index"), 'checked' => false)
											);
											foreach( $index_settings as $key => $val ) {
												$checked = (get_option($key) == "on" || $val['checked'] == true && get_option($key) != "off") ? 'checked' : '' ;
												echo '<input id="'.$key.'" class="checkbox of-input" type="checkbox" '.$checked.' name="'.$key.'"><label class="explain checkbox_label" for="'.$key.'">'.$val['title'].'</label><br>';
											}
										?>
										</div>
										<div class="explain">
											<p>
												<?php echo theme_locals("forbid"); ?>
											</p>
											<p>
												<?php echo theme_locals("these_settings"); ?>
											</p>
										</div>
									</div>
									<h4 class="heading">Link settings</h4>
									<div class="option clearfix">
										<div class="controls">
										<?php
											$content_settings = array(
												'add_nofollow' => array('title' => theme_locals("nofollow_name"), 'checked' => false), 
											);
											foreach( $content_settings as $key => $val ) {
												$checked = (get_option($key) == "on" || $val['checked'] == true && get_option($key) != "off") ? 'checked' : '' ;
												echo '<input id="'.$key.'" class="checkbox of-input" type="checkbox" '.$checked.' name="'.$key.'"><label class="explain checkbox_label" for="'.$key.'">'.$val['title'].'</label><br>';
											}
										?>
										</div>
										<div class="explain">
											<p>
												<?php echo theme_locals("nofollow_desc"); ?>
											</p>
										</div>
									</div>
								</div>
							</div>
							<div id="sitemap-xml" class="group">
								<h3><?php echo theme_locals("sitemap_xml"); ?></h3>
								<div class="section">
									<h4 class="heading"><?php echo theme_locals("generate_sitemap_title"); ?></h4>
									<div class="option">
										<div class="controls">
											<?php
												$sitemap_settings = array(
													'do_generate_sitemap' => array('title' => theme_locals("generate_sitemap"), 'checked' => true),
												);
												foreach( $sitemap_settings as $key => $val ) {
													$checked = (get_option($key) == "on" || $val['checked'] == true && get_option($key) != "off") ? 'checked' : '' ;
													echo '<input id="'.$key.'" class="checkbox of-input" type="checkbox" '.$checked.' name="'.$key.'"><label class="explain checkbox_label" for="'.$key.'">'.$val['title'].'</label><br>';
												}
											?>
										</div>
									</div>
								</div>
								<div class="section">
									<h4 class="heading"><?php echo theme_locals("post_types_settings"); ?></h4>
									<div class="option">
										<div class="controls">
											<header class="group_options">
												<div class="unitu"><?php echo theme_locals("include_post_types"); ?></div>
												<div class="unitu"><?php echo theme_locals("priority"); ?></div>
												<div class="unitu"><?php echo theme_locals("change_freq"); ?></div>
											</header>
											<?php
												$post_types = array_merge(array('test' => '', 'page' => '', 'post' => '', 'services' => '', 'portfolio' => '', 'slider' => '', 'team' => '', 'testi' => '', 'faq' => ''), get_post_types(array('public'   => true, '_builtin' => false), 'objects', 'or'));
												$priority_array = array(0, 0.1, 0.2, 0.3, 0.4, 0.5, 0.6, 0.7, 0.8, 0.9, 1);
												$changefreq_array = array('always', 'hourly', 'daily', 'weekly', 'monthly', 'yearly', 'never');
												unset($post_types['wpcf7_contact_form'], $post_types['optionsframework'], $post_types['attachment']);
												
												foreach( $post_types as $post_type ) {
													if(!empty($post_type)){
														$checked = (get_option('checked_'.$post_type->name) == "on") ? 'checked' : '' ;
														echo '<div class="group_options"><div class="unitu"><input id="'.$post_type->name.'" class="checkbox of-input" type="checkbox" '.$checked.' name="checked_'.$post_type->name.'">';
														echo '<label class="explain checkbox_label" for="'.$post_type->name.'">'.$post_type->labels->name.'</label></div>';
														echo '<div class="unitu"><select class="of-typography-character" name="priority_'.$post_type->name.'">';
															foreach( $priority_array as $priority ) {
																$selected = get_option('priority_'.$post_type->name) == $priority ? 'selected' : '' ;
																echo '<option value="'.$priority.'" '.$selected.'>'.$priority.'</option>';
															}
														echo '</select></div>';
														echo '<div class="unitu"><select class="of-typography-character" name="changefreq_'.$post_type->name.'">';
															foreach( $changefreq_array as $changefreq ) {
																$selected = get_option('changefreq_'.$post_type->name) == $changefreq ? 'selected' : '' ;
																echo '<option value="'.$changefreq.'" '.$selected.'>'.$changefreq.'</option>';
															}
														echo '</select></div></div>';
													}
												}
											?>
										</div>
										<div class="explain">
											<p>
												<?php echo theme_locals("include_post_types_desc"); ?>
											</p>
											<p>
												<?php echo theme_locals("priority_desc"); ?>
											</p>
											<p>
												<?php echo theme_locals("change_freq_desc"); ?>
											</p>
										</div>
									</div>
								</div>
								<div class="section">
									<h4 class="heading"><?php echo theme_locals("ping_sitemap"); ?></h4>
									<div class="option">
										<div class="controls">
										<?php
											$search_sistems = array('google' => 'Google', 'yandex' => 'Yandex', 'yahoo' => 'Yahoo!', 'bing' => 'Bing', 'ask' => 'Ask.com');
											foreach( $search_sistems as $key => $val ) {
												$checked = (get_option($key.'_ping') == "on") ? 'checked' : '' ;
												echo '<input id="'.$key.'" class="checkbox of-input" type="checkbox" '.$checked.' name="'.$key.'_ping"><label class="explain checkbox_label" for="'.$key.'"><span class="icon_'.$key.'"></span>'.$val.'</label><br>';
											}
										?>
										</div>
										<div class="explain">
											<p>
												<?php echo theme_locals("ping_sitemap_desc"); ?>
											</p>
										</div>
									</div>
								</div>
							</div>
							<div id="optionsframework-submit" class="clearfix">
								<div class='button_wrapper fright'>
									<input type="submit" class="button-primary" name="save_options" value="Save Options">
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
			<?php
		}
	}
	add_action('wp_ajax_save_options', 'cherry_save_options');
	if (!function_exists('cherry_save_options')) {
		function cherry_save_options() {
			$post_data = $_POST;
			unset($post_data['action']);
			foreach ($post_data as $key => $val) {
				update_option($key, $val);
			}
			//echo $post_data['do_generate_sitemap'];
			robot_txt_generate();
			generate_sitemap();
			exit;
		} 
	}

	// page java script
	add_action('admin_footer', 'page_script');
	if (!function_exists('page_script')) {
		function page_script() {
			?>
			<script>
				function add_click_ajax(objects, data){
					jQuery.ajax({
						url:ajaxurl,
						type: "POST",
						data: data,
						beforeSend: function() {
							objects.css({visibility: "hidden"}).parent().css({background: 'url("images/wpspin_light.gif") center no-repeat', boxShadow: 'inset 0px 0px 10px 5px #E5E5E5', borderRadius: 3});
						},
						success: function(d) {
							console.log(d);
							objects.parent().css({background: 'url("images/yes.png") center no-repeat'});
							setTimeout(function() {
								objects.css({visibility: "visible"}).parent().css({background: "none", boxShadow: 'none'});
							}, 1000)
						}
					});
				}
				jQuery('#optionsframework-submit input[name="save_options"]').on('click', function (){
					var data = {action: 'save_options'};

					jQuery('#optionsframework-metabox input, #optionsframework-metabox select, #optionsframework-metabox textarea').not('input[type="submit"]').each(function(){
						var item = jQuery(this),
							value = item.val();
						if(value=='on' && item[0].checked == false){
							value='off';
						}
						data[item[0].name] = value;
					});

					add_click_ajax(jQuery(this), data);

					return !1;
				});
			</script>
			<?php 
		}
	}