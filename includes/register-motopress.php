<?php

if ( ! class_exists( 'TGM_Plugin_Activation' ) ) {
	require_once PARENT_DIR . '/includes/class-tgm-plugin-activation.php';
}

add_action( 'tgmpa_register', 'theme_register_motopress_ce_lite_plugin', 11);

function theme_register_motopress_ce_lite_plugin() {
	$plugins = array(
		array(
			'name'               => 'MotoPress Content Editor', // The plugin name
			'slug'               => 'motopress-content-editor', // The plugin slug (typically the folder name)
			'source'             => PARENT_DIR . '/includes/plugins/motopress-content-editor.zip', // The plugin source
			'required'           => false, // If false, the plugin is only 'recommended' instead of required
			'version'            => '', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
			'force_activation'   => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
			'force_deactivation' => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
			'external_url'       => 'http://www.getmotopress.com/content-editor/', // If set, overrides default API URL and points to an external URL
		)
	);

	if ( !function_exists( 'is_plugin_active' ) ) {
		require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	}

	if ( ! is_plugin_active( 'motopress-content-editor/motopress-content-editor.php') ) {
		$message = '';
		if ( isset(TGM_Plugin_Activation::$instance) && isset(TGM_Plugin_Activation::$instance->message) ) {
			$message = TGM_Plugin_Activation::$instance->message;
		}

		$message .= '<div class="updated"><p>' . __('MotoPress Content Editor is a drag and drop visual builder for creating and editing your WordPress posts and pages.<br/>Note: MotoPress Content Editor <b>customizes the content created only by this plugin</b>. To edit previously created content you should use the default WordPress editor.', 'cherry') . '</p></div>';

		$config = array('message' => $message);
		tgmpa( $plugins, $config );
	}
	else {
		tgmpa( $plugins );
	}
}