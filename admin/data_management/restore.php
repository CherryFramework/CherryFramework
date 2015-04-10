<?php
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die('Error');
}

add_action( 'wp_ajax_cherry_restore', 'cherry_restore_callback' );
function cherry_restore_callback() {
	$theme_folder = isset( $_GET['theme_folder'] ) ? $_GET['theme_folder'] : '';

	if ( !$theme_folder ) {
		wp_die( 'File not provided', 'Error' );
	}

	$file          = str_replace('\\', '/', WP_CONTENT_DIR).'/themes_backup/'.$theme_folder.".zip";
	$themes_folder = str_replace('\\', '/', get_theme_root()).'/'.$theme_folder;

	if ( file_exists( $file ) ) {
		chery_remove_dir( $themes_folder );
		cherry_unzip_backup( $file, $themes_folder );
	} else {
		echo theme_locals("unfortunately").$theme_folder.theme_locals("please_try");
	}
}

function cherry_unzip_backup( $file, $themes_folder ) {
	$zip = new PclZip( $file );

	if ( $zip->extract( PCLZIP_OPT_PATH, $themes_folder ) == 0 ) {
		die("Error : ".$zip->errorInfo(true));
	}

	echo get_option( PARENT_NAME . "_version_backup" );
}

function chery_remove_dir( $path ) {
	return is_file( $path ) ? @unlink( $path ) : array_map( 'chery_remove_dir', glob( $path."/*" ) ) == @rmdir( $path );
} ?>