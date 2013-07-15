<?php
    if (isset($_GET["theme_folder"])){ 
        $theme_folder = $_GET["theme_folder"];
    }
	include_once ('../../../../../wp-load.php');
	$file = str_replace('\\', '/', WP_CONTENT_DIR).'/themes_backup/'.$theme_folder.".zip";
    if(file_exists($file)){
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . basename($file));
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        readfile($file);
    }else {
       echo theme_locals("unfortunately").$theme_folder.theme_locals("please_try");
    };
?>