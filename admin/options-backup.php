<?php
/*
 *
 * Options Framework Theme - Options Backup
 *
 * Backup your "Theme Options" to a downloadable text file.
 *
 * @version 1.0.0
 * @author Gilles Vauvarin
 *
 * This code is a fork from the WooThemes Framework admin-backup.php file.
 *
 * -----------------------------------------------------------------------------------

 TABLE OF CONTENTS

 - var $admin_page
 - var $token
 
 - function OptionsFramework_Backup () 						// Constructor
 - function init () 										// Initialize the class.
 - function register_admin_screen () 						// Register the admin screen within WordPress.
 - function admin_screen () 								// Load the admin screen.
 - function admin_screen_help ()							// Add contextual help to the admin screen.
 - function admin_notices() 								// Display admin notices when performing backup/restore.
 - function admin_screen_logic ()							// The processing code to generate the backup or restore from a previous backup.	
 - function import ()										// Import settings from a backup file.
 - function export ()										// Export settings to a backup file.
 - function construct_database_query ()						// Constructs the database query based on the export type.

 - Create $woo_backup Object
-----------------------------------------------------------------------------------*/

require_once dirname( __FILE__ ) . '/widget-data.php';
require_once dirname( __FILE__ ) . '/wordpress-importer.php';

class OptionsFramework_Backup {
	
	var $admin_page_import;
	var $admin_page_export;
	var $token;
	
	function OptionsFramework_Backup () {
		$this->admin_page_import = '';
		$this->admin_page_export = '';
		$this->token = 'options-backup';
	} // End Constructor
	
	/**
	 * init()
	 *
	 * Initialize the class.
	 *
	 * @since 1.0.0
	 */
	
	function init () {
		if ( is_admin() && ( get_option( 'framework_woo_backupmenu_disable' ) != 'true' ) ) {

			$myWidget_Data = new myWidget_Data();

			// Register the admin screen.
			add_action('admin_menu', array( &$this, 'register_admin_screen' ), 20 );
			// add_action('admin_enqueue_scripts', array($this, 'add_admin_scripts'));
			add_action('wp_ajax_import_widget_data', array($myWidget_Data, 'ajax_import_widget_data'));
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
		add_action("admin_print_styles-$this->admin_page_import",'optionsframework_load_adminstyles');
		add_action("admin_print_styles-$this->admin_page_export",'optionsframework_load_adminstyles');

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
		add_action( 'load-' . $this->admin_page_import, array( &$this, 'admin_screen_logic' ) );
		add_action( 'load-' . $this->admin_page_export, array( &$this, 'admin_screen_logic' ) );
		
		// Add contextual help.
		//add_action( 'contextual_help', array( &$this, 'admin_screen_help' ), 10, 3 );
		add_action( 'admin_notices', array( &$this, 'admin_notices' ), 10 );
	
	} // End register_admin_screen()
	
	/**
	 * admin_screen_import()
	 *
	 * Load the admin screen import.
	 *
	 * @since 1.0.0
	 */
	
	function admin_screen_import () {
	
		$export_type = 'all';
		
		if ( isset( $_POST['export-type'] ) ) {
			$export_type = esc_attr( $_POST['export-type'] );
		}
	?>
	<div id="optionsframework-metabox" class="metabox-holder">
		<div id="optionsframework" class="postbox">
			<div class="wrap">
				<!--?php echo get_screen_icon( $screen = 'import-export' ); ?-->
				<h3><?php echo theme_locals("import"); ?></h3>
				
				<div class="import">
					<?php $step = empty( $_GET['step'] ) ? 1 : (int) $_GET['step'];
					$myClass = new MY_Import();
						switch ( $step ) {
							// case 0:
							// 	$this->callImportTheme();
							// 	break;
							case 1:
								$this->callImportWidget();
								break;
							case 2:
								$this->callImportData();
								break;
							case 3:
								$this->callImportData();
								break;
							case 4:
								$this->callImportData();
								break;
							case 5:
								$this->callImportData();
								break;
						}?>
				</div>
				<div class="progress-holder">
					<div class="progress progress-striped">
						<div class="bar" style="width:0%"></div>
						<div class="circle step1 in-progress"><span>1</span></div>
						<div class="circle step2"><span>2</span></div>
						<!-- <div class="circle step3"><span>3</span></div> -->
						<div class="circle finish"><span><?php echo theme_locals("done"); ?></span></div>
					</div>
				</div><!-- /.progress-holder -->
			</div><!--/.wrap-->
		</div>
	</div>
	<?php
	
	} // End admin_screen_import()

	function callImportTheme () { ?>
		<div class="import-options-settings">
			<h4 class="head"><?php echo theme_locals("Step_1"); ?></h4>
			<p class="text-style"><?php echo theme_locals("Select the file that contains Theme Options"); ?></p>
	
			<form enctype="multipart/form-data" method="post" action="<?php echo admin_url( 'admin.php?page=options-framework-import'); ?>" id="optionsForm">
				<div class="indent-bot">
					<?php wp_nonce_field( 'OptionsFramework-backup-import' ); ?>
					<input type="file" id="OptionsFramework-import-file" name="OptionsFramework-import-file" size="25">
					<input type="hidden" name="OptionsFramework-backup-import" value="1">
				</div>
				<input type="submit" class="button-primary" value="<?php echo theme_locals("Upload File and Import"); ?>" disabled="disabled">
			</form>
		</div>
	<?php 
	}

	function callImportWidget () {
		$myWidget_Data = new myWidget_Data();
		$myWidget_Data->import_settings_page();
	}

	function callImportData () {
		$myImport_Data = new MY_Import();
		$myImport_Data->dispatch();
	}


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
				<!--?php echo get_screen_icon( $screen = 'import-export' ); ?-->
				<h3><?php echo theme_locals("export"); ?></h3>
				
				<div class="export">
		
				<?php $step = empty( $_GET['step'] ) ? 1 : (int) $_GET['step'];
					switch ( $step ) {
						// case 0:
						// 	$this->callExportTheme();
						// 	break;
						case 1:
							$this->callExportWidget();
							break;
						case 2:
							$this->callExportData();
							break;
					}?>
				</div>
			</div><!--/.wrap-->
		</div>
	</div>
<?php
	
	} // End admin_screen_export()

	function callExportTheme () { ?>
		<h4 class="head"><?php echo theme_locals("Step_1_export"); ?></h4>
		<form method="post" action="" id="theme-options-export">
			<?php wp_nonce_field( 'OptionsFramework-backup-export' ); ?>
			<input type="hidden" name="OptionsFramework-backup-export" value="1" />
			<input type="submit" class="button-primary fnone" value="<?php echo theme_locals("next"); ?>" />
		</form>
	<?php }

	function callExportWidget () { ?>
		<?php 
			$myWidget_Data = new myWidget_Data();
			$myWidget_Data->export_settings_page(); ?>
	<?php }

	function callExportData () {
		wp_redirect( admin_url( 'export.php' ) ); ?>
	<?php }
	
	/**
	 * admin_screen_help()
	 *
	 * Add contextual help to the admin screen.
	 *
	 * @since 1.0.0
	 */
	
	/*function admin_screen_help ( $contextual_help, $screen_id, $screen ) {
	
		// $contextual_help .= var_dump($screen); // use this to help determine $screen->id
		
		if ( $this->admin_page == $screen->id ) {
		
		$contextual_help =
		  '<h3>' .theme_locals("Welcome to the OptionsFramework Backup Manager"). '</h3>';		
		} // End IF Statement
		
		return $contextual_help;
	
	} // End admin_screen_help()
	*/
	
	/**
	 * admin_notices()
	 *
	 * Display admin notices when performing backup/restore.
	 *
	 * @since 1.0.0
	 */
	
	function admin_notices () {
	
		if ( ! isset( $_GET['page'] ) || ( $_GET['page'] != 'options-framework-import' ) ) { return; }
			
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
	 * The processing code to generate the backup or restore from a previous backup.
	 *
	 * @since 1.0.0
	 */
	
	function admin_screen_logic () {
		
		if ( ! isset( $_POST['OptionsFramework-backup-export'] ) && isset( $_POST['OptionsFramework-backup-import'] ) && ( $_POST['OptionsFramework-backup-import'] == true ) ) {
			$this->import();
		}
		
		if ( ! isset( $_POST['OptionsFramework-backup-import'] ) && isset( $_POST['OptionsFramework-backup-export'] ) && ( $_POST['OptionsFramework-backup-export'] == true ) ) {
			$this->export();
		}

		if ( isset ( $_POST['Widget-Settings-export'] ) && ( $_POST['Widget-Settings-export'] == true ) )
		{
			$myWidget_Data = new myWidget_Data();
			$myWidget_Data->export_widget_settings();
		}

	} // End admin_screen_logic()
	

	
	/**
	 * import()
	 *
	 * Import settings from a backup file.
	 *
	 * @since 1.0.0
	 */



	function import() {
		check_admin_referer( 'OptionsFramework-backup-import' ); // Security check.
		
		if ( ! isset( $_FILES['OptionsFramework-import-file'] ) ) { return; } // We can't import the settings without a settings file.
		
		// Extract file contents
		$upload = file_get_contents( $_FILES['OptionsFramework-import-file']['tmp_name'] );
		
		// Decode the JSON from the uploaded file
		$datafile = json_decode( $upload, true );
		
		// Check for errors
		if ( ! $datafile || $_FILES['OptionsFramework-import-file']['error'] ) {
			wp_redirect( admin_url( 'admin.php?page=options-framework-import&error=true' ) );
			exit;
		}
		
		// Make sure this is a valid backup file.
		if ( ! isset( $datafile['OptionsFramework-backup-validator'] ) ) {
			wp_redirect( admin_url( 'admin.php?page=options-framework-import&invalid=true' ) );
			exit;
		} else {
			unset( $datafile['OptionsFramework-backup-validator'] ); // Now that we've checked it, we don't need the field anymore.
		}

		
		// Get the theme name from the database.
		$optionsframework_data = get_option('optionsframework');
		$optionsframework_name = $optionsframework_data['id'];
		//$optionsframework_name = get_option( $optionsframework_name );
		
		// Update the settings in the database
		update_option( $optionsframework_name, '' );
		if ( update_option( $optionsframework_name, $datafile ) ) {
		
		// Redirect, add success flag to the URI
			wp_redirect( admin_url( 'admin.php?page=options-framework-import&imported=true&step=1' ) );
			exit;
		} else {
		// Errors: update fail
			var_dump($optionsframework_name);
			wp_redirect( admin_url( 'admin.php?page=options-framework-import&error=true' ) );
			exit;
		}
		
	} // End import()

	
	/**
	 * export()
	 *
	 * Export settings to a backup file.
	 *
	 * @since 1.0.0
	 * @uses global $wpdb
	 */
	 
	function export() {
		global $wpdb;
		check_admin_referer( 'OptionsFramework-backup-export' ); // Security check.
		
		$optionsframework_settings = get_option('optionsframework');
		$database_options = get_option( $optionsframework_settings['id'] );
		
		// Error trapping for the export.
		if ( $database_options == '' ) {
			wp_redirect( admin_url( 'admin.php?page=options-framework-export&error-export=true' ) );
			return;
		}
		
		if ( ! $database_options ) { return; }


	
		// Add our custom marker, to ensure only valid files are imported successfully.
		$database_options['OptionsFramework-backup-validator'] = date( 'Y-m-d h:i:s' );
	
		// Generate the export file.
		$output = json_encode( (array)$database_options );
	
		header( 'Content-Description: File Transfer' );
		header( 'Cache-Control: public, must-revalidate' );
		header( 'Pragma: hack' );
		header( 'Content-Type: text/plain' );
		header( 'Content-Disposition: attachment; filename="options.json"' );
		header( 'Content-Length: ' . strlen( $output ) );
		echo $output;
		exit;
	} // End export()

} // End Class

/**
 * Create $woo_backup Object.
 *
 * @since 1.0.0
 * @uses OptionsFramework_Backup
 */

$of_backup = new OptionsFramework_Backup();
$of_backup->init();
?>