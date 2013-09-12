<?php
	include_once ('../../../../../wp-load.php');
	include_once (ABSPATH . '/wp-admin/includes/class-pclzip.php');
	if (isset($_GET["theme_folder"])){ 
		$theme_folder = $_GET["theme_folder"];
	}
	create_zip ($theme_folder);

	function create_zip ($themeName){
		$exclude_files = array('.', '..', '.svn', 'thumbs.db', '!sources', 'style.less.cache', 'bootstrap.less.cache', '.gitignore', '.git');
		$all_themes_dir = str_replace('\\', '/', get_theme_root());
		$backup_dir = str_replace('\\', '/', WP_CONTENT_DIR).'/themes_backup';
		$zip_name = $backup_dir."/".$themeName.'.zip';
		$backup_date = date("F d Y");
		
		if(is_dir($all_themes_dir."/".$themeName)){
			$file_string = scan_dir($all_themes_dir."/".$themeName, $exclude_files);
		}

		if ( function_exists('wp_get_theme') ) {
			$backup_version = wp_get_theme($themeName)->Version;
		} else {
			$backup_version = get_current_theme($themeName)->Version;
		}

		if(!is_dir($backup_dir)){
			if(mkdir($backup_dir, 0700)){
				$htaccess_file = fopen($backup_dir.'/.htaccess', 'a');
				$htaccess_text = 'deny from all';
				fwrite($htaccess_file, $htaccess_text);
				fclose($htaccess_file);
			}
		}

		$zip = new PclZip($zip_name);
		if ($zip->create($file_string, PCLZIP_OPT_REMOVE_PATH, $all_themes_dir."/".$themeName) == 0) {
			die("Error : ".$zip->errorInfo(true)); 
		}

		update_option($themeName."_date_backup", $backup_date, '', 'yes');
		update_option($themeName."_version_backup", $backup_version, '', 'yes');
		
		echo $backup_date.",".$backup_version;
	}
	function scan_dir ($dir, $exceptions_array){
		$scand_dir = scandir($dir);
		$scan_dir_string = array();
		foreach ($scand_dir as $file) {
			if(!in_array(strtolower($file), $exceptions_array)){
				$scan_file = $dir.'/'.$file;
				if(is_dir($scan_file)){
					$scan_file= scan_dir ($scan_file, $exceptions_array);
				}
				array_push($scan_dir_string, $scan_file);
			}
		}
		return implode(',', $scan_dir_string);
	}
?>