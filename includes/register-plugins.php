<?php
/**
 * This file represents an example of the code that themes would use to register
 * the required plugins.
 *
 * It is expected that theme authors would copy and paste this code into their
 * functions.php file, and amend to suit.
 *
 * @package	   TGM-Plugin-Activation
 * @subpackage Example
 * @version	   2.3.6
 * @author	   Thomas Griffin <thomas@thomasgriffinmedia.com>
 * @author	   Gary Jones <gamajo@gamajo.com>
 * @copyright  Copyright (c) 2012, Thomas Griffin
 * @license	   http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link       https://github.com/thomasgriffin/TGM-Plugin-Activation
 */

/**
 * Include the TGM_Plugin_Activation class.
 */
require_once PARENT_DIR . '/includes/class-tgm-plugin-activation.php';

add_action( 'tgmpa_register', 'my_theme_register_required_plugins' );
/**
 * Register the required plugins for this theme.
 *
 * In this example, we register two plugins - one included with the TGMPA library
 * and one from the .org repo.
 *
 * The variable passed to tgmpa_register_plugins() should be an array of plugin
 * arrays.
 *
 * This function is hooked into tgmpa_init, which is fired within the
 * TGM_Plugin_Activation class constructor.
 */
function my_theme_register_required_plugins() {
	/**
	 * Array of plugin arrays. Required keys are name and slug.
	 * If the source is NOT from the .org repo, then source is also required.
	 */
	$plugins = array(
		// This is an example of how to include a plugin pre-packaged with a theme
		array(
			'name'               => 'Contact Form 7', // The plugin name
			'slug'               => 'contact-form-7', // The plugin slug (typically the folder name)
			'source'             => CHILD_DIR . '/includes/plugins/contact-form-7.zip', // The plugin source
			'required'           => true, // If false, the plugin is only 'recommended' instead of required
			'version'            => '', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
			'force_activation'   => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
			'force_deactivation' => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
			'external_url'       => '', // If set, overrides default API URL and points to an external URL
		),
		array(
			'name'               => 'Cherry Plugin', // The plugin name
			'slug'               => 'cherry-plugin', // The plugin slug (typically the folder name)
			'source'             => CHILD_DIR . '/includes/plugins/cherry-plugin.zip', // The plugin source
			'required'           => true, // If false, the plugin is only 'recommended' instead of required
			'version'            => '0.1', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
			'force_activation'   => true, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
			'force_deactivation' => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
			'external_url'       => '', // If set, overrides default API URL and points to an external URL
		),
	);

	/**
	 * Array of configuration settings. Amend each line as needed.
	 * If you want the default strings to be available under your own theme domain,
	 * leave the strings uncommented.
	 * Some of the strings are added into a sprintf, so see the comments at the
	 * end of each line for what each argument will be.
	 */
	$config = array(
		'domain'           => CURRENT_THEME, // Text domain - likely want to be the same as your theme.
		'default_path'     => '', // Default absolute path to pre-packaged plugins
		'parent_menu_slug' => 'themes.php', // Default parent menu slug
		'parent_url_slug'  => 'themes.php', // Default parent URL slug
		'menu'             => 'install-required-plugins', // Menu slug
		'has_notices'      => true, // Show admin notices or not
		'is_automatic'     => true, // Automatically activate plugins after installation or not
		'message'          => '', // Message to output right before the plugins table
		'strings'          => array(
			'page_title'                      => theme_locals("page_title"),
			'menu_title'                      => theme_locals("menu_title"),
			'installing'                      => theme_locals("installing"), // %1$s = plugin name
			'oops'                            => theme_locals("oops_2"),
			'notice_can_install_required'     => _n_noop( theme_locals("notice_can_install_required"), theme_locals("notice_can_install_required_2") ), // %1$s = plugin name(s)
			'notice_can_install_recommended'  => _n_noop( theme_locals("notice_can_install_recommended"), theme_locals("notice_can_install_recommended_2") ), // %1$s = plugin name(s)
			'notice_cannot_install'           => _n_noop( theme_locals("notice_cannot_install"), theme_locals("notice_cannot_install_2") ), // %1$s = plugin name(s)
			'notice_can_activate_required'    => _n_noop( theme_locals("notice_can_activate_required"), theme_locals("notice_can_activate_required_2") ), // %1$s = plugin name(s)
			'notice_can_activate_recommended' => _n_noop( theme_locals("notice_can_activate_recommended"), theme_locals("notice_can_activate_recommended_2") ), // %1$s = plugin name(s)
			'notice_cannot_activate'          => _n_noop( theme_locals("notice_cannot_activate"), theme_locals("notice_cannot_activate_2") ), // %1$s = plugin name(s)
			'notice_ask_to_update'            => _n_noop( theme_locals("notice_ask_to_update"), theme_locals("notice_ask_to_update_2") ), // %1$s = plugin name(s)
			'notice_cannot_update'            => _n_noop( theme_locals("notice_cannot_update"), theme_locals("notice_cannot_update_2") ), // %1$s = plugin name(s)
			'install_link'                    => _n_noop( theme_locals("install_link"), theme_locals("install_link_2") ),
			'activate_link'                   => _n_noop( theme_locals("activate_link"), theme_locals("activate_link_2") ),
			'return'                          => theme_locals("return"),
			'plugin_activated'                => theme_locals("plugin_activated"),
			'complete'                        => theme_locals("complete"), // %1$s = dashboard link
			'nag_type'                        => theme_locals("updated") // Determines admin notice type - can only be 'updated' or 'error'
		)
	);
	tgmpa( $plugins, $config );
}