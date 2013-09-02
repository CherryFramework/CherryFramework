<?php
/*
 * Options Framework Theme
 *
-----------------------------------------------------------------------------------*/

require_once dirname( __FILE__ ) . '/widget-data.php';
require_once dirname( __FILE__ ) . '/wordpress-importer.php';

class OptionsFramework {

	var $admin_page_import;
	var $admin_page_export;

	function OptionsFramework () {
		$this->admin_page_import = '';
		$this->admin_page_export = '';
	} // End Constructor

	/**
	 * init()
	 *
	 * Initialize the class.
	 *
	 * @since 1.0.0
	 */
	function init () {
		if ( is_admin() ) {
			global $cherry_widget_data;
			$cherry_widget_data = new myWidget_Data();

			// Register the admin screen.
			add_action('admin_menu', array( &$this, 'register_admin_screen' ), 20 );
			add_action('wp_ajax_import_widget_data', array($cherry_widget_data, 'ajax_import_widget_data'));
			add_action('load-options-permalink.php', array(&$this, 'hide_setup_wp_pointers'));
			add_action('load-themes.php', array(&$this, 'unlink_log'));
		}
	} // End init()
	
	/**
	 * register_admin_screen()
	 *
	 * Register the admin screen within WordPress.
	 *
	 * @since 1.0.0
	 */
	function register_admin_screen () {

		$this->admin_page_import = add_submenu_page('options-framework', theme_locals("import"), theme_locals("import"), 'administrator', 'options-framework-import',  array(&$this, 'admin_screen_import'));
		$this->admin_page_export = add_submenu_page('options-framework', theme_locals("export"), theme_locals("export"), 'administrator', 'options-framework-export',  array(&$this, 'admin_screen_export'));

		// Adds actions to hook in the required css and javascript
		add_action("admin_print_styles-$this->admin_page_import", 'optionsframework_load_adminstyles');
		add_action("admin_print_styles-$this->admin_page_export", 'optionsframework_load_adminstyles');

		add_action("admin_print_scripts-$this->admin_page_import", 'optionsframework_load_adminscripts');
		add_action("admin_print_scripts-$this->admin_page_export", 'optionsframework_load_adminscripts');

		/* Loads the CSS */
		function optionsframework_load_adminstyles() {
			wp_enqueue_style('optionsframework', OPTIONS_FRAMEWORK_DIRECTORY.'css/optionsframework.css');
		}

		/* Loads the JS */
		function optionsframework_load_adminscripts () {
			wp_enqueue_script( 'setup', OPTIONS_FRAMEWORK_DIRECTORY.'js/setup.js', array('jquery'), '1.0');
		}
		// Admin screen logic.
		add_action("load-$this->admin_page_import", array( &$this, 'admin_screen_logic' ));
		add_action("load-$this->admin_page_export", array( &$this, 'admin_screen_logic' ));
		
		// Add admin notices.
		add_action( 'admin_notices', array( &$this, 'admin_notices' ), 10 );
	
	} // End register_admin_screen()
	
	/**
	 * admin_screen_import()
	 *
	 * Load the admin screen import.
	 *
	 * @since 1.0.0
	 */
	function admin_screen_import () { ?>
		<div id="optionsframework-metabox" class="metabox-holder">
			<div id="optionsframework" class="postbox">
				<div class="wrap">
					<?php if ( array_key_exists('success', $_GET) ) {
						$this->log('Importing process are finish. Our Congratulations!');
						$this->success();
					} else {
						$step = empty( $_GET['step'] ) ? 1 : (int) $_GET['step']; ?>
						<h3><?php echo theme_locals("import"); ?></h3>
						<div class="import">
						<?php if ( $step > 0 && $step < 5 ) {
							$this->call_import_data();
							$this->progressbar();
						} else {
							$page = empty( $_GET['page'] ) ? 'options-framework' : $_GET['page'];
							$this->oops($page);
						} ?>
						</div><!--/.import-->
					<?php } ?>
				</div><!--/.wrap-->
			</div><!--/#optionsframework-->
		</div><!--/#optionsframework-metabox-->
	<?php
	} // End admin_screen_import()

	/**
	 * admin_screen_export()
	 *
	 * Load the admin screen export.
	 *
	 * @since 1.0.0
	 */
	function admin_screen_export () { ?>
		<div id="optionsframework-metabox" class="metabox-holder">
			<div id="optionsframework" class="postbox">
				<div class="wrap">
					<h3><?php echo theme_locals("export"); ?></h3>
					<div class="export">
					<?php $step = empty( $_GET['step'] ) ? 1 : (int) $_GET['step'];
						switch ( $step ) {
							case 1:
								$this->call_export_widget();
								break;
							case 2:
								$this->call_export_data();
								break;
							default:
								$page = empty( $_GET['page'] ) ? 'options-framework' : $_GET['page'];
								$this->oops($page);
								break;
						}
					?>
					</div>
				</div><!--/.wrap-->
			</div>
		</div>
	<?php
	} // End admin_screen_export()

	/**
	 * success()
	 *
	 * Load the admin screen success.
	 *
	 * @since 1.0.0
	 */
	function success() { ?>
		<h3 class="success-title"><?php echo theme_locals("congratulations"); ?>!</h3>
		<div class="success-wrap">
			<p class="text-style"><?php echo theme_locals('congratulations_msg'); ?></p>
			<a class="reset-button button-secondary" href="<?php echo admin_url('admin.php?page=options-framework'); ?>"><?php echo theme_locals('pointer_close'); ?></a>
			<a class="button-primary" href="<?php echo home_url(); ?>" target="_blank"><?php echo theme_locals('visit_site'); ?></a>
		</div>
	<?php
	} // End admin_screen_success()

	/**
	 * progressbar()
	 *
	 * Load the progressbar.
	 *
	 * @since 1.0.0
	 */
	function progressbar() { ?>
		<div class="progress-holder">
			<div class="progress progress-striped">
				<div class="bar" style="width:0%"></div>
				<div class="circle start in-progress"><span></span></div>
				<div class="circle step1"><span>1</span></div>
				<div class="circle step2"><span>2</span></div>
				<div class="circle step3"><span>3</span></div>
				<div class="circle finish"><span><?php echo theme_locals("done"); ?></span></div>
			</div>
		</div><!-- /.progress-holder -->
	<?php } // End progressbar()

	/**
	 * oops()
	 *
	 * Load the oops screen.
	 *
	 * @since 1.0.0
	 */
	function oops($page) { ?>
		<div class="oops-holder">
			<h4>Oops!</h4>
			<a href="<?php echo 'admin.php?page='.$page; ?>" class="btn-link"><?php echo theme_locals('try_again'); ?></a>
		</div><!-- /.oops-holder -->
	<?php }// End oops()

	// function call_import_widget () {
	// 	global $cherry_widget_data;
	// 	$cherry_widget_data->import_settings_page();
	// }

	function call_import_data () {
		$cherry_import_data = new MY_Import();
		$cherry_import_data->dispatch();
	}

	function call_export_widget () {
		global $cherry_widget_data;
		$cherry_widget_data->export_settings_page();
	}

	function call_export_data () {
		wp_redirect( admin_url( 'export.php' ) );
		exit;
	}

	/**
	 * admin_notices()
	 *
	 * Display admin notices when performing backup/restore.
	 *
	 * @since 1.0.0
	 */
	function admin_notices () {

		if ( ! isset( $_GET['page'] ) 
			|| ( $_GET['page'] != 'options-framework-import' ) 
			|| ( $_GET['page'] != 'options-framework-export' ) ) { 
			return;
		}

		if ( isset( $_GET['error'] ) && $_GET['error'] == 'true' ) {
			echo '<div id="message" class="error"><p>' . theme_locals("problem_importing") . '</p></div>';
		} else if ( isset( $_GET['error-export'] ) && $_GET['error-export'] == 'true' ) {  
			echo '<div id="message" class="error"><p>' . theme_locals("problem_exporting") . '</p></div>';
		} else if ( isset( $_GET['invalid'] ) && $_GET['invalid'] == 'true' ) {  
			echo '<div id="message" class="error"><p>' . theme_locals("provided_is_invalid") . '</p></div>';
		} // End IF Statement

	} // End admin_notices()
	
	/**
	 * admin_screen_logic()
	 *
	 * The processing code to generate the backup for widget settings.
	 *
	 * @since 1.0.0
	 */
	function admin_screen_logic () {
		global $cherry_widget_data;

		if ( isset($_POST['Widget-Settings-export']) && ( $_POST['Widget-Settings-export'] == true ) ) {
			$cherry_widget_data->export_widget_settings();
		}
	} // End admin_screen_logic()

	/**
	 * hide_setup_wp_pointers()
	 *
	 * Hiding the help pointers
	 *
	 * @since 1.0.0
	 */
	function hide_setup_wp_pointers() {
		if( isset($_GET['settings-updated']) && $_GET['settings-updated'] ) {
			$setup_wp_pointers = array('xyz1', 'xyz2', 'xyz3');
			$dismissed = explode( ',', (string) get_user_meta( get_current_user_id(), 'dismissed_wp_pointers', true ) );
			foreach ($setup_wp_pointers as $value) {
				if (!in_array($value, $dismissed))
					array_push($dismissed, $value);
			}
			$new_dismissed = implode(',', $dismissed);
			update_user_meta(get_current_user_id(), 'dismissed_wp_pointers', $new_dismissed);
			if ( get_option('cherry_sample_data') == 1 ) {
				update_option('cherry_sample_data', 2);
				wp_redirect( admin_url( 'admin.php?page=options-framework-import&success' ) );
				exit;
			}
		}
	}

	/**
	 *
	 * Write to log file
	 *
	 */
	function log($message) {
		$new_message = date('Y-m-d H:i:s');
		$new_message .= PHP_EOL . $message . PHP_EOL;
		$log_file = CHILD_DIR . '/install.log';
		if (is_writable(CHILD_DIR)) {
			file_put_contents($log_file, $new_message . PHP_EOL, FILE_APPEND);
		}
	} // End log()

	/*
	 *
	 * Unlink log file
	 *
	 */
	function unlink_log() {
		if ( (!get_option('cherry_sample_data')) && FILE_WRITEABLE ) {
			$log_file = CHILD_DIR .'/install.log';
			if (file_exists($log_file)) 
				unlink($log_file);
		}
	}
} // End Class

/**
 *
 * @since 1.0.0
 * @uses OptionsFramework
 */
$of_backup = new OptionsFramework();
$of_backup->init();
?>