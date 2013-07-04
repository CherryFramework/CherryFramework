<?php

	class myWidget_Data {
		
		function __construct() {
			add_action('admin_enqueue_scripts', array($this, 'add_admin_scripts'));
		}

		function add_admin_scripts () {
			wp_register_script( 'widget_data', OPTIONS_FRAMEWORK_DIRECTORY.'js/widget_data.js', array( 'jquery', 'wp-ajax-response' ));
			wp_enqueue_script( 'widget_data' );
			$widgets_url = get_admin_url(false, 'widgets.php');
			wp_localize_script( 'widget_data', 'widgets_url', $widgets_url);
		}		

		function export_settings_page() {
			$sidebar_widgets = $this->order_sidebar_widgets(wp_get_sidebars_widgets());
			?>
			<div class="widget-data export-widget-settings">
				<div class="wrap">
					<h4 class="head"><?php echo theme_locals("step_1_export_widget"); ?></h4>
					<form action="" method="post" id="widget-export-settings">
						<?php wp_nonce_field( 'Widget-Settings-export' ); ?>
						<input type="hidden" name="Widget-Settings-export" value="1" />

						<div style="clear:both;"></div>
						<div class="sidebars indent-bot">
							<?php
							foreach ($sidebar_widgets as $sidebar_name=>$widget_list) :
								if (count($widget_list) == 0)
									continue;

								$sidebar_info = $this->get_sidebar_info($sidebar_name);?>

								<div class="sidebar">
									<h4><?php echo $sidebar_info['name']; ?></h4>

									<div class="widgets">
										<?php foreach ($widget_list as $widget) :

											$widget_type = trim(substr($widget, 0, strrpos($widget, '-')));
											$widget_type_index = trim(substr($widget, strrpos($widget, '-') + 1));
											$widget_options = get_option( 'widget_' . $widget_type );
											$widget_title = isset( $widget_options[$widget_type_index]['title'] ) ? $widget_options[$widget_type_index]['title'] : $widget_type_index;

										?>
											<div class="import-form-row">
												<input class="<?php echo ($sidebar_name == 'wp_inactive_widgets') ? 'inactive' : 'active'; ?> widget-checkbox" type="checkbox" name="<?php echo esc_attr( $widget ); ?>" id="<?php echo esc_attr( 'meta_' .  $widget ); ?>" <?php echo ($sidebar_name == 'wp_inactive_widgets') ? '' : 'checked'; ?> />
												<label for="<?php echo esc_attr( 'meta_' . $widget ); ?>">
													<?php 
														echo ucfirst( $widget_type );
														if( !empty( $widget_title ) )
															echo ' - ' . $widget_title; 
													?>
												</label>
											</div>
										<?php endforeach; ?>
									</div> <!-- end widgets -->
								</div> <!-- end sidebar -->
							<?php endforeach;?>
						</div> <!-- end sidebars -->
						<button class="button-primary fnone" type="submit" name="export-widgets" id="export-widgets"><?php echo theme_locals("next"); ?>&raquo;</button>
					</form>
				</div> <!-- end wrap -->
			</div> <!-- end export-widget-settings -->
			<?php
		}
		
		function import_settings_page() {
			?>
			<div class="widget-data import-widget-settings">
				<h4 class="head"><?php echo theme_locals("step_1_import_widget"); ?></h4>					
				
				<?php if (isset($_FILES['upload-file'])) : ?>
				<div class="import-wrapper">
				<div style="clear:both;"></div>
					<form action="" id="import-widget-data" method="post">
						<?php 
							wp_nonce_field('import_widget_data', '_wpnonce');

							$json = $this->get_widget_settings_json();

							if( is_wp_error($json) )
								wp_die( $json->get_error_message() );

							if( !( $json_data = json_decode( $json[0], true ) ) )
								return;

							$json_file = $json[1];
						?>
						<input type="hidden" name="import_file" value="<?php echo esc_attr( $json_file ); ?>"/>
						<input type="hidden" name="action" value="import_widget_data"/>
						
						<?php							
						if (isset ($json_data[0])) : ?>
							<div class="sidebars indent-bot">
							<?php foreach ($this->order_sidebar_widgets($json_data[0]) as $sidebar_name=>$widget_list) :
									if (count($widget_list) == 0)
										continue;

									$sidebar_info = $this->get_sidebar_info($sidebar_name);?>									
									<?php if ($sidebar_info) : ?>
										<div class="sidebar">
											<h4><?php echo $sidebar_info['name']; ?></h4>

											<div class="widgets">
												<?php foreach ($widget_list as $widget) :
													$widget_options = false;

													$widget_type = trim(substr($widget, 0, strrpos($widget, '-')));
													$widget_type_index = trim(substr($widget, strrpos($widget, '-') + 1));
													$option_name = 'widget_'.$widget_type;
													$widget_type_options = $this->get_option_from_array($widget_type, $json_data[1]);
													if ($widget_type_options) :
														$widget_title = isset($widget_type_options[$widget_type_index]['title']) ? $widget_type_options[$widget_type_index]['title'] : '';
														$widget_options = $widget_type_options[$widget_type_index];
													endif;
													?>
												<div class="import-form-row">
														<input class="<?php echo ($sidebar_name == 'wp_inactive_widgets') ? 'inactive' : 'active'; ?>" type="checkbox" name="widgets[<?php echo $widget_type; ?>][<?php echo $widget_type_index; ?>]" id="meta_<?php echo $widget; ?>" <?php echo ($sidebar_name == 'wp_inactive_widgets') ? '' : 'checked'; ?> />
														<label for="meta_<?php echo $widget; ?>">&nbsp;
															<?php
															echo ucfirst($widget_type);

															if (!empty($widget_title)) :
																echo (' - '.$widget_title);
															else :
																echo (' - '.$widget_type_index);
															endif;
															?>
														</label>
												</div>
												<?php endforeach; ?>
											</div> <!-- end widgets -->
										</div> <!-- end sidebar -->
									<?php endif; ?>
								<?php endforeach; ?>							
							</div> <!-- end sidebars -->
							<input class="button-primary" type="submit" name="import-widgets" id="import-widgets" value="<?php echo theme_locals("import_settings"); ?>">
							<?php else :
								echo theme_locals("please_try_again");
							endif; ?>
						</form>
					</div>
					<?php else : ?>
						<form action="" id="upload-widget-data" method="post" enctype="multipart/form-data">
							<div class="indent-bot">
								<p class="text-style"><?php echo theme_locals("select_the_file"); ?></p>
									<input type="file" name="upload-file" id="upload-file" size="25">
								</div>
							<input type="submit" name="button-upload" class="button-primary" value="<?php echo theme_locals("show_widget_settings"); ?>" disabled="disabled">
						</form>
						<form enctype="multipart/form-data" id="skip-import-widget" method="post" action="admin.php?page=options-framework-import&step=2">
							<p class="submit"><input type="submit" class="button" value="<?php echo theme_locals("skip"); ?>"></p>
						</form>
					<?php endif; ?>
			</div> <!-- end import-widget-settings -->
			<?php
		}

		/**
		 * Retrieve widgets from sidebars and create JSON object
		 * @param array $posted_array
		 * @return string 
		 */
		function parse_export_data( $posted_array ) {
			$sidebars_array = get_option( 'sidebars_widgets' );
			$sidebar_export = array( );
			foreach ( $sidebars_array as $sidebar => $widgets ) {
				if ( !empty( $widgets ) && is_array( $widgets ) ) {
					foreach ( $widgets as $sidebar_widget ) {
						if ( in_array( $sidebar_widget, array_keys( $posted_array ) ) ) {
							$sidebar_export[$sidebar][] = $sidebar_widget;
						}
					}
				}
			}
			$widgets = array( );
			foreach ( $posted_array as $k => $v ) {
				$widget = array( );
				$widget['type'] = trim( substr( $k, 0, strrpos( $k, '-' ) ) );
				$widget['type-index'] = trim( substr( $k, strrpos( $k, '-' ) + 1 ) );
				$widget['export_flag'] = ($v == 'on') ? true : false;
				$widgets[] = $widget;
			}
			$widgets_array = array( );
			foreach ( $widgets as $widget ) {
				$widget_val = get_option( 'widget_' . $widget['type'] );
				$multiwidget_val = $widget_val['_multiwidget'];
				$widgets_array[$widget['type']][$widget['type-index']] = $widget_val[$widget['type-index']];
				if ( isset( $widgets_array[$widget['type']]['_multiwidget'] ) ) {
					unset( $widgets_array[$widget['type']]['_multiwidget'] );
				}
				$widgets_array[$widget['type']]['_multiwidget'] = $multiwidget_val;
			}
			unset( $widgets_array['export'] );
			$export_array = array( $sidebar_export, $widgets_array );
			error_log(var_export($export_array, true));
			$json = json_encode( $export_array );
			return $json;
		}

		/**
		 * Import widgets
		 * @param array $import_array
		 */
		function parse_import_data( $import_array ) {
			$sidebars_data = $import_array[0];
			$widget_data = $import_array[1];
			$current_sidebars = get_option( 'sidebars_widgets' );
			$new_widgets = array( );

			foreach ( $sidebars_data as $import_sidebar => $import_widgets ) :

				foreach ( $import_widgets as $import_widget ) :
					//if the sidebar exists
					if ( isset( $current_sidebars[$import_sidebar] ) ) :
						$title = trim( substr( $import_widget, 0, strrpos( $import_widget, '-' ) ) );
						$index = trim( substr( $import_widget, strrpos( $import_widget, '-' ) + 1 ) );
						$current_widget_data = get_option( 'widget_' . $title );
						$new_widget_name = self::get_new_widget_name( $title, $index );
						$new_index = trim( substr( $new_widget_name, strrpos( $new_widget_name, '-' ) + 1 ) );

						if ( !empty( $new_widgets[ $title ] ) && is_array( $new_widgets[$title] ) ) {
							while ( array_key_exists( $new_index, $new_widgets[$title] ) ) {
								$new_index++;
							}
						}
						$current_sidebars[$import_sidebar][] = $title . '-' . $new_index;
						if ( array_key_exists( $title, $new_widgets ) ) {
							$new_widgets[$title][$new_index] = $widget_data[$title][$index];
							$multiwidget = $new_widgets[$title]['_multiwidget'];
							unset( $new_widgets[$title]['_multiwidget'] );
							$new_widgets[$title]['_multiwidget'] = $multiwidget;
						} else {
							$current_widget_data[$new_index] = $widget_data[$title][$index];
							$current_multiwidget = $current_widget_data['_multiwidget'];
							$new_multiwidget = $widget_data[$title]['_multiwidget'];
							$multiwidget = ($current_multiwidget != $new_multiwidget) ? $current_multiwidget : 1;
							unset( $current_widget_data['_multiwidget'] );
							$current_widget_data['_multiwidget'] = $multiwidget;
							$new_widgets[$title] = $current_widget_data;
						}

					endif;
				endforeach;
			endforeach;

			if ( isset( $new_widgets ) && isset( $current_sidebars ) ) {
				update_option( 'sidebars_widgets', $current_sidebars );

				foreach ( $new_widgets as $title => $content )
					update_option( 'widget_' . $title, $content );

				return true;
			}
			return false;
		}

		/**
		 * Output the JSON for download
		 */
		function export_widget_settings() {
			// @TODO check something better than just $_POST
			// if ( isset( $_POST['action'] ) && $_POST['action'] == 'export_widget_settings' ){
				header( "Content-Description: File Transfer" );
				header( "Content-Disposition: attachment; filename=widgets.json" );
				header( "Content-Type: application/octet-stream" );
				echo $this->parse_export_data( $_POST );
				exit;
			// }
		}

		/**
		 * Parse JSON import file and load
		 */
		function ajax_import_widget_data() {
			$response = array(
				'what' => 'widget_import_export',
				'action' => 'import_submit'
			);

			$widgets = isset( $_POST['widgets'] ) ? $_POST['widgets'] : false;
			$import_file = isset( $_POST['import_file'] ) ? $_POST['import_file'] : false;

			if( empty($widgets) || empty($import_file) ){
				$response['id'] = new WP_Error('import_widget_data', 'No widget data posted to import');
				$response = new WP_Ajax_Response( $response );
				$response->send();
			}

			$json_data = file_get_contents( $import_file );
			$json_data = json_decode( $json_data, true );
			$sidebar_data = $json_data[0];
			$widget_data = $json_data[1];
			foreach ( $sidebar_data as $title => $sidebar ) {
				$count = count( $sidebar );
				for ( $i = 0; $i < $count; $i++ ) {
					$widget = array( );
					$widget['type'] = trim( substr( $sidebar[$i], 0, strrpos( $sidebar[$i], '-' ) ) );
					$widget['type-index'] = trim( substr( $sidebar[$i], strrpos( $sidebar[$i], '-' ) + 1 ) );
					if ( !isset( $widgets[$widget['type']][$widget['type-index']] ) ) {
						unset( $sidebar_data[$title][$i] );
					}
				}
				$sidebar_data[$title] = array_values( $sidebar_data[$title] );
			}

			foreach ( $widgets as $widget_title => $widget_value ) {
				foreach ( $widget_value as $widget_key => $widget_value ) {
					$widgets[$widget_title][$widget_key] = $widget_data[$widget_title][$widget_key];
				}
			}

			$sidebar_data = array( array_filter( $sidebar_data ), $widgets );
			$response['id'] = ( $this->parse_import_data( $sidebar_data ) ) ? true : new WP_Error( 'widget_import_submit', 'Unknown Error' );

			$response = new WP_Ajax_Response( $response );
			$response->send();
		}

		/**
		 * Read uploaded JSON file
		 * @return type 
		 */
		function get_widget_settings_json() {
			$widget_settings = $this->upload_widget_settings_file();
			if (array_key_exists('error', $widget_settings)){
				echo "<b>".theme_locals("error").": </b>" . $widget_settings['error'];
			}
			if (array_key_exists('file', $widget_settings)){
				$file_contents = file_get_contents($widget_settings['file']);
				return array($file_contents, $widget_settings['file']);
			}
		}

		/**
		 * Upload JSON file
		 * @return boolean 
		 */
		function upload_widget_settings_file() {
			if ( isset( $_FILES['upload-file'] ) ) {
				add_filter( 'upload_mimes', array( $this, 'json_upload_mimes' ) );

				$upload = wp_handle_upload( $_FILES['upload-file'], array( 'test_form' => false ) );

				remove_filter( 'upload_mimes', array( $this, 'json_upload_mimes' ) );
				return $upload;
			}

			return false;
		}

		/**
		 *
		 * @param string $widget_name
		 * @param string $widget_index
		 * @return string 
		 */
		function get_new_widget_name( $widget_name, $widget_index ) {
			$current_sidebars = get_option( 'sidebars_widgets' );
			$all_widget_array = array( );
			foreach ( $current_sidebars as $sidebar => $widgets ) {
				if ( !empty( $widgets ) && is_array( $widgets ) && $sidebar != 'wp_inactive_widgets' ) {
					foreach ( $widgets as $widget ) {
						$all_widget_array[] = $widget;
					}
				}
			}
			while ( in_array( $widget_name . '-' . $widget_index, $all_widget_array ) ) {
				$widget_index++;
			}
			$new_widget_name = $widget_name . '-' . $widget_index;
			return $new_widget_name;
		}

		function get_option_from_array($option_name, $array_options) {
			foreach ($array_options as $name=>$option) :
				if ($name == $option_name)
					return $option;
			endforeach;

			return false;
		}

		/**
		 *
		 * @global type $wp_registered_sidebars
		 * @param type $sidebar_id
		 * @return boolean 
		 */
		function get_sidebar_info( $sidebar_id ) {
			global $wp_registered_sidebars;

			//since wp_inactive_widget is only used in widgets.php
			if ( $sidebar_id == 'wp_inactive_widgets' )
				return array( 'name' => 'Inactive Widgets', 'id' => 'wp_inactive_widgets' );

			foreach ( $wp_registered_sidebars as $sidebar ) {
				if ( isset( $sidebar['id'] ) && $sidebar['id'] == $sidebar_id )
					return $sidebar;
			}
			return false;
		}

		/**
		 *
		 * @param array $sidebar_widgets
		 * @return type 
		 */
		function order_sidebar_widgets( $sidebar_widgets ) {
			$inactive_widgets = false;

			//seperate inactive widget sidebar from other sidebars so it can be moved to the end of the array, if it exists
			if ( isset( $sidebar_widgets['wp_inactive_widgets'] ) ) {
				$inactive_widgets = $sidebar_widgets['wp_inactive_widgets'];
				unset( $sidebar_widgets['wp_inactive_widgets'] );
				$sidebar_widgets['wp_inactive_widgets'] = $inactive_widgets;
			}

			return $sidebar_widgets;
		}

		/**
		 * Add mime type for JSON
		 * @param array $existing_mimes
		 * @return string 
		 */
		function json_upload_mimes( $existing_mimes = array( ) ) {
			$existing_mimes['json'] = 'application/json';
			return $existing_mimes;
		}
}