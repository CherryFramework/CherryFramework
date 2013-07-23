<?php 
	include_once ('../../../../../wp-load.php');
	include_once (ABSPATH . '/wp-admin/includes/class-pclzip.php');

	if (isset($_GET["theme_folder"])){ 
		$theme_folder = $_GET["theme_folder"];
	}
	$file = str_replace('\\', '/', WP_CONTENT_DIR).'/themes_backup/'.$theme_folder.".zip";
	$themes_folder = str_replace('\\', '/', get_theme_root()).'/'.$theme_folder;

	if(file_exists($file)){
		removeDir($themes_folder);
		unzip($file, $themes_folder);
	}else {
       echo theme_locals("unfortunately").$theme_folder.theme_locals("please_try");
    };

	function unzip($file, $themes_folder) {
			$zip = new PclZip($file);
		    if ($zip->extract(PCLZIP_OPT_PATH, $themes_folder) == 0) {
		       die("Error : ".$zip->errorInfo(true));
		   	}
		   	echo get_option(PARENT_NAME."_version_backup");
	}
    function removeDir($path) {
	    return is_file($path)?@unlink($path):array_map('removeDir',glob($path."/*"))==@rmdir($path);
	}
?>