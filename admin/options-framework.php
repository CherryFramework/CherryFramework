<?php
/*
Description: A framework for building theme options.
Author: Devin Price
Author URI: http://www.wptheming.com
License: GPLv2
Version: 1.3
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

/* If the user can't edit theme options, no use running this plugin */

add_action('init', 'optionsframework_rolescheck' );

function optionsframework_rolescheck () {
	if ( current_user_can( 'edit_theme_options' ) ) {
		// If the user can edit theme options, let the fun begin!
		add_action( 'admin_menu', 'optionsframework_add_page');
		add_action( 'admin_menu', 'optionsframework_add_data_managment');
		add_action( 'admin_menu', 'optionsframework_add_seo');
		add_action( 'admin_init', 'optionsframework_init' );
		add_action( 'admin_init', 'optionsframework_mlu_init' );
		add_action( 'wp_before_admin_bar_render', 'optionsframework_adminbar' );
	}
}

/* Loads the file for option sanitization */

add_action('init', 'optionsframework_load_sanitization' );

function optionsframework_load_sanitization() {
	require_once dirname( __FILE__ ) . '/options-sanitize.php';
}

/*
 * Creates the settings in the database by looping through the array
 * we supplied in options.php.  This is a neat way to do it since
 * we won't have to save settings for headers, descriptions, or arguments.
 *
 * Read more about the Settings API in the WordPress codex:
 * http://codex.wordpress.org/Settings_API
 *
 */

function optionsframework_init() {

	// Include the required files
	require_once dirname( __FILE__ ) . '/options-interface.php';
	require_once dirname( __FILE__ ) . '/options-medialibrary-uploader.php';

	// Loads the options array from the theme
	if ( $optionsfile = locate_template( array('options.php') ) ) {
		require_once($optionsfile);
	}
	else if (file_exists( dirname( __FILE__ ) . '/options.php' ) ) {
		require_once dirname( __FILE__ ) . '/options.php';
	}

	$optionsframework_settings = get_option('optionsframework' );

	// Updates the unique option id in the database if it has changed
	optionsframework_option_name();

	// Gets the unique id, returning a default if it isn't defined
	if ( isset($optionsframework_settings['id']) ) {
		$option_name = $optionsframework_settings['id'];
	}
	else {
		$option_name = 'optionsframework';
	}

	// If the option has no saved data, load the defaults
	if ( ! get_option($option_name) ) {
		optionsframework_setdefaults();
	}

	// Registers the settings fields and callback
	if (!isset( $_POST['OptionsFramework-backup-import'] )) {
		register_setting( 'optionsframework', $option_name, 'optionsframework_validate' );
	}

	// Registers the settings fields and callback
	register_setting( 'optionsframework', $option_name, 'optionsframework_validate' );

	// Change the capability required to save the 'optionsframework' options group.
	add_filter( 'option_page_capability_optionsframework', 'optionsframework_page_capability' );
}

/**
 * Ensures that a user with the 'edit_theme_options' capability can actually set the options
 * See: http://core.trac.wordpress.org/ticket/14365
 *
 * @param string $capability The capability used for the page, which is manage_options by default.
 * @return string The capability to actually use.
 */

function optionsframework_page_capability( $capability ) {
	return 'edit_theme_options';
}

/*
 * Adds default options to the database if they aren't already present.
 * May update this later to load only on plugin activation, or theme
 * activation since most people won't be editing the options.php
 * on a regular basis.
 *
 * http://codex.wordpress.org/Function_Reference/add_option
 *
 */

function optionsframework_setdefaults() {
	$optionsframework_settings = get_option('optionsframework');

	// Gets the unique option id
	$option_name = $optionsframework_settings['id'];

	/*
	 * Each theme will hopefully have a unique id, and all of its options saved
	 * as a separate option set.  We need to track all of these option sets so
	 * it can be easily deleted if someone wishes to remove the plugin and
	 * its associated data.  No need to clutter the database.
	 *
	 */

	if ( isset($optionsframework_settings['knownoptions']) ) {
		$knownoptions =  $optionsframework_settings['knownoptions'];
		if ( !in_array($option_name, $knownoptions) ) {
			array_push( $knownoptions, $option_name );
			$optionsframework_settings['knownoptions'] = $knownoptions;
			update_option('optionsframework', $optionsframework_settings);
		}
	} else {
		$newoptionname = array($option_name);
		$optionsframework_settings['knownoptions'] = $newoptionname;
		update_option('optionsframework', $optionsframework_settings);
	}

	// Gets the default options data from the array in options.php
	$options = combined_option_array();

	// If the options haven't been added to the database yet, they are added now
	$values = of_get_default_values();

	if ( isset($values) ) {
		add_option( $option_name, $values ); // Add option with default settings
	}
}

/* Add a menu page called "Cherry Options" */

if ( !function_exists( 'optionsframework_add_page' ) ) {
	function optionsframework_add_page() {
		$of_page = add_menu_page(theme_locals("cherry_options"), theme_locals("cherry_options"), 'edit_theme_options', 'options-framework', 'optionsframework_page', OPTIONS_FRAMEWORK_DIRECTORY.'images/cherry-icon.png', 61);

		// Adds actions to hook in the required css and javascript
		add_action("admin_print_scripts-$of_page", 'optionsframework_load_scripts');
		add_action("admin_print_styles-$of_page",'optionsframework_load_styles');
	}
}
/* Add a subpage called "Data Management" to the Cherry Options. */

if ( !function_exists( 'optionsframework_add_data_managment' ) ) {
	function optionsframework_add_data_managment () {
		$of_page = add_submenu_page('options-framework', theme_locals("data_management"), theme_locals("data_management"), 'administrator', 'options-framework-data-management',  'admin_data_management');

		// Adds actions to hook in the required css and javascript
		add_action("admin_print_scripts-$of_page", 'optionsframework_load_scripts');
		add_action("admin_print_styles-$of_page",'optionsframework_load_styles');
	}
}

/* Add a subpage called "SEO" to the Cherry Options. */

if ( !function_exists( 'optionsframework_add_seo' ) ) {
	function optionsframework_add_seo () {
		$of_page = add_submenu_page('options-framework', 'SEO', 'SEO', 'administrator', 'seo',  'seo_settings_page');
		// Adds actions to hook in the required css and javascript
		add_action("admin_print_scripts-$of_page", 'optionsframework_load_scripts_seo');
		add_action("admin_print_styles-$of_page",'optionsframework_load_styles_seo');
	}
}

/* Loads the CSS */

function optionsframework_load_styles() {
	wp_enqueue_style('optionsframework', OPTIONS_FRAMEWORK_DIRECTORY.'css/optionsframework.css');
	wp_enqueue_style('color-picker', OPTIONS_FRAMEWORK_DIRECTORY.'css/colorpicker.css');
}

function optionsframework_load_styles_seo() {
	wp_enqueue_style('optionsframework', OPTIONS_FRAMEWORK_DIRECTORY.'css/optionsframework.css');
}

/* Loads the javascript */

function optionsframework_load_scripts() {

	// Enqueued scripts
	wp_enqueue_script('jquery-ui-core');
	wp_enqueue_script('color-picker', OPTIONS_FRAMEWORK_DIRECTORY.'js/colorpicker.js', array('jquery'));
	wp_enqueue_script('options-custom', OPTIONS_FRAMEWORK_DIRECTORY.'js/options-custom.js', array('jquery'));
	wp_localize_script( 'options-custom', 'ajaxurl', admin_url( 'admin-ajax.php' ) );

	// Inline scripts from options-interface.php
	add_action('admin_head', 'of_admin_head');
}

function optionsframework_load_scripts_store() {
	// Enqueued scripts
	wp_enqueue_script('core', '//www.templatehelp.com/codes/jsbanner/a04/js/core.js', array('jquery'));
	wp_enqueue_script('jcarousel', '//www.templatehelp.com/codes/jsbanner/a04/js/jquery.jcarousel.min.js', array('jquery'));
	wp_enqueue_script('ajaxbanner', '//www.templatehelp.com/codes/jsbanner/a04/js/ajaxbanner.js', array('jquery'));
	wp_enqueue_script('ajaxbannerjquery', '//www.templatehelp.com/codes/jsbanner/a04/ajaxbanner.php?banner_id=jsbanner&features=cherry-framework&property[2553][0]=42645&type=17&category=&package=&types=17&orientation=horizontal&skin=blue&pr_code=yB4zGJx5Q0cY73K5N8GC8r3n6BI91a&unbranded=0&size=5&count=30&pr_code=4j5VV9LLkf2aUvBh1TnnTxwbf3xX1C', array('jquery'));

	// Inline scripts from options-interface.php
	add_action('admin_head', 'of_admin_head');
}

function optionsframework_load_scripts_seo() {
	// Enqueued scripts
	wp_enqueue_script('jquery-ui-core');
	wp_enqueue_script('options-custom', OPTIONS_FRAMEWORK_DIRECTORY.'js/options-custom.js', array('jquery'));
	// Inline scripts from options-interface.php
	add_action('admin_head', 'of_admin_head');
}

function of_admin_head() {

	// Hook to add custom scripts
	do_action( 'optionsframework_custom_scripts' );
}

/*
 * Builds out the options panel.
 *
 * If we were using the Settings API as it was likely intended we would use
 * do_settings_sections here.  But as we don't want the settings wrapped in a table,
 * we'll call our own custom optionsframework_fields.  See options-interface.php
 * for specifics on how each individual field is generated.
 *
 * Nonces are provided using the settings_fields()
 *
 */

if ( !function_exists( 'optionsframework_page' ) ) {
	function optionsframework_page() {
		settings_errors();
?>

	<div id="optionsframework-wrap" class="wrap">
		<div class="extern-links">
			<?php
				$support_link = '//info.template-help.com/help/cms-blog-templates/wordpress/wordpress-tutorials/';
				$doc_link = '//info.template-help.com/help/quick-start-guide/wordpress-themes/master/index_en.html';
				if (class_exists('Woocommerce')) {
					$doc_link = '//www.templatemonster.com/help/quick-start-guide/woocommerce-themes/master/index_en.html';
				} elseif (function_exists('jigoshop_init')) {
					$doc_link = '//www.templatemonster.com/help/quick-start-guide/jigoshop-themes/master/index_en.html';
				}

				$language = get_bloginfo("language");
				switch ($language) {
					case 'ru-RU':
						$support_link = '//info.template-help.com/help/ru/cms-blog-templates/wordpress/wordpress-tutorials/';
						break;
					case 'es-ES':
						$support_link = '//info.template-help.com/help/es/cms-blog-templates/wordpress/wordpress-tutorials/';
						break;
					case 'de-DE':
						$support_link = '//info.template-help.com/help/de/cms-blog-templates/wordpress/wordpress-tutorials/';
					break;
				}

				echo '<a class="icon-a icon-support" href="'.$support_link.'" target="_blank"><span class="icon"><span>'.theme_locals("support").'</span></span></a>';
				echo "<a class='icon-a icon-documentation' href='".$doc_link."' target='_blank'><span class='icon'><span>".theme_locals('documentation')."</span></span></a>";
			?>
		</div>
		<div class="clear"></div>

	<h2 class="nav-tab-wrapper">
		<?php echo optionsframework_tabs(); ?>
	</h2>

	<div id="optionsframework-metabox" class="metabox-holder">
		<div id="optionsframework" class="postbox">
			<form action="options.php" method="post">
			<?php settings_fields('optionsframework'); ?>
			<?php optionsframework_fields(); /* Settings */ ?>
			<div id="optionsframework-submit">
				<input type="submit" class="button-primary" name="update" value="<?php echo theme_locals("save_options"); ?>" />
				<input type="submit" class="reset-button button-secondary" name="reset" value="<?php echo theme_locals("restore_defaults"); ?>" onclick="return confirm( '<?php echo theme_locals("restore_defaults_desc"); ?>' );" />
				<div class="clear"></div>
			</div>
			</form>
		</div> <!-- / #container -->
	</div>
	<?php do_action('optionsframework_after'); ?>
	</div> <!-- / .wrap -->
<?php
	}
}

/**
 * Validate Options.
 *
 * This runs after the submit/reset button has been clicked and
 * validates the inputs.
 *
 * @uses $_POST['reset'] to restore default options
 */
function optionsframework_validate( $input ) {

	/*
	 * Restore Defaults.
	 *
	 * In the event that the user clicked the "Restore Defaults"
	 * button, the options defined in the theme's options.php
	 * file will be added to the option for the active theme.
	 */

	if ( isset( $_POST['reset'] ) ) {
		add_settings_error( 'options-framework', 'restore_defaults', theme_locals("default_options"), 'updated fade' );
		return of_get_default_values();
	}

	/*
	 * Update Settings
	 *
	 * This used to check for $_POST['update'], but has been updated
	 * to be compatible with the theme customizer introduced in WordPress 3.4
	 */

	$clean = array();
	$options = combined_option_array();

	foreach ( $options as $option ) {

		if ( ! isset( $option['id'] ) ) {
			continue;
		}

		if ( ! isset( $option['type'] ) ) {
			continue;
		}

		$id = preg_replace( '/[^a-zA-Z0-9._\-]/', '', strtolower( $option['id'] ) );

		// Set checkbox to false if it wasn't sent in the $_POST
		if ( 'checkbox' == $option['type'] && ! isset( $input[$id] ) ) {
			$input[$id] = false;
		}

		// Set each item in the multicheck to false if it wasn't sent in the $_POST
		if ( 'multicheck' == $option['type'] && ! isset( $input[$id] ) ) {
			foreach ( $option['options'] as $key => $value ) {
				$input[$id][$key] = false;
			}
		}

		// For a value to be submitted to database it must pass through a sanitization filter
		if ( has_filter( 'of_sanitize_' . $option['type'] ) && isset( $input[$id] ) ) {
			$clean[$id] = apply_filters( 'of_sanitize_' . $option['type'], $input[$id], $option );
		}
	}
	add_settings_error( 'options-framework', 'save_options', theme_locals("options_saved"), 'updated fade' );

	// Hook to run after validation
	do_action( 'optionsframework_after_validate' );

	return $clean;
}

/**
 * Format Configuration Array.
 *
 * Get an array of all default values as set in
 * options.php. The 'id','std' and 'type' keys need
 * to be defined in the configuration array. In the
 * event that these keys are not present the option
 * will not be included in this function's output.
 *
 * @return    array     Rey-keyed options configuration array.
 *
 * @access    private
 */

function of_get_default_values() {
	$output = array();
	$config = combined_option_array();

	foreach ( (array) $config as $option ) {
		if ( ! isset( $option['id'] ) ) {
			continue;
		}
		if ( ! isset( $option['std'] ) ) {
			continue;
		}
		if ( ! isset( $option['type'] ) ) {
			continue;
		}
		if ( has_filter( 'of_sanitize_' . $option['type'] ) ) {
			$output[$option['id']] = apply_filters( 'of_sanitize_' . $option['type'], $option['std'], $option );
		}
	}
	do_action( 'optionsframework_after_validate' );
	return $output;
}

/**
 * Add Theme Options menu item to Admin Bar.
 */

function optionsframework_adminbar() {

	global $wp_admin_bar;

	$wp_admin_bar->add_menu( array(
			'parent' => 'appearance',
			'id' => 'of_theme_options',
			'title' => theme_locals("cherry_options"),
			'href' => admin_url( 'admin.php?page=options-framework' )
		));
}

if ( ! function_exists( 'of_get_option' ) ) {

	/**
	 * Get Option.
	 *
	 * Helper function to return the theme option value.
	 * If no value has been saved, it returns $default.
	 * Needed because options are saved as serialized strings.
	 */

	function of_get_option( $name, $default = false ) {
		$config = get_option( 'optionsframework' );

		if ( ! isset( $config['id'] ) ) {
			return $default;
		}

		$options = get_option( $config['id'] );

		if ( isset( $options[$name] ) ) {
			return $options[$name];
		}

		return $default;
	}
}
?>
