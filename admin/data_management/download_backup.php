<?php
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die('Error');
}

add_action( 'wp_ajax_cherry_download_backup', 'cherry_download_backup_callback' );
function cherry_download_backup_callback() {
	$theme_folder = isset( $_GET["theme_folder"] ) ? $_GET["theme_folder"] : '';

	if ( !$theme_folder ) {
		wp_die( 'File not provided', 'Error' );
	}

	$file = str_replace( '\\', '/', WP_CONTENT_DIR ) . '/themes_backup/' . $theme_folder . ".zip";

	if ( file_exists( $file ) ) {
		$nonce    = wp_create_nonce( 'cherry_download_backup', 'wp_nonce_download_backup' );
		$file_url = add_query_arg( array( 'action' => 'cherry_prepare_download_backup', 'file' => $file, '_wpnonce' => $nonce ), admin_url( 'admin-ajax.php' ) );
		echo $file_url;
	} else {
		echo "error";
	}
	exit();
}

add_action( 'wp_ajax_cherry_prepare_download_backup', 'cherry_prepare_download_backup_callback' );
function cherry_prepare_download_backup_callback() {
	check_ajax_referer( 'cherry_download_backup', 'wp_nonce_download_backup' );

	if ( !current_user_can( 'export' ) ) {
		wp_die( 'You do not have permissions to do this', 'Error' );
	}

	$file = isset( $_GET['file'] ) ? $_GET['file'] : '';

	if ( !$file ) {
		wp_die( 'File not provided', 'Error' );
	}

	if ( file_exists( $file ) ) {
		header('Content-Description: File Transfer');
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename=' . basename($file));
		header('Content-Transfer-Encoding: binary');
		header('Expires: 0');
		header('Cache-Control: must-revalidate');
		header('Pragma: public');
		header('Content-Length: ' . filesize($file));
		readfile($file);
	} else {
		echo theme_locals("unfortunately") . $theme_folder . theme_locals("please_try");
	}
	exit();
} ?>