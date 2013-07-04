<?php
	include_once (PARENT_DIR . '/includes/data_management/update.php');
	/*include_once (PARENT_DIR . '/includes/data_management/backup.php');
	include_once (PARENT_DIR . '/includes/data_management/restore.php');
	include_once (PARENT_DIR . '/includes/data_management/download_backup.php');*/

	class WPhackersSE_Theme_Folders{ 
		public function start_up(){
	        add_filter( 'theme_action_links', array( $this, 'theme_folder_single_site' ), 10, 2 );
	    }
	    public function theme_folder_single_site( $actions, $theme ){
	    	$theme_template = $theme->template;
	    	if(strtolower($theme_template) == "cherryframework"){
	    		$backup_theme =	$theme->stylesheet;
		    	$backup_button = '<a href="../wp-content/themes/'.$theme_template.'/includes/data_management/backup.php?theme_folder=/'.$backup_theme.'" title="'.theme_locals("backup").'" class="backup_theme">'.theme_locals("backup").'</a>';
		        array_push($actions, $backup_button);
	    	}
	    	return $actions;
	    }
	}
	$add_backup_button = new WPhackersSE_Theme_Folders;
	$add_backup_button->start_up();
?>