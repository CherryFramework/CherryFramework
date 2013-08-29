<?php
// robot.txt generater
	if(!function_exists('robot_txt_generate')){
		function robot_txt_generate(){
			$user_agent_code = "User-agent: Yandex";
			$admni_url_code = "\r\nDisallow: /wp-admin\r\nDisallow: /wp-includes\r\nDisallow: /wp-login.php\r\nDisallow: /wp-register.php";
			$theme_url_code = "\r\nDisallow: /wp-content/themes";
			$plagin_url_code = "\r\nDisallow: /wp-content/plugins";
			$media_url_code = "\r\nDisallow: /wp-content/uploads";
			$disallow_url_code = "\r\nDisallow: /wp-content/upgrade\r\nDisallow: /wp-content/themes_backup\r\nDisallow: /wp-comments\r\nDisallow: /cgi-bin\r\nDisallow: *?s=";
			$host_code = "\r\nHost: ".$_SERVER["SERVER_NAME"];
			$sitemap_code = "\r\n";
			$robot_txt_code = "";

			for ($i=0; $i < 2; $i++) {
				if($i==1){
					$user_agent_code = "\r\nUser-agent: *";
					$host_code = '';
					$sitemap_code = (get_option('sitemap_done')=="true") ? "\r\n\r\nSitemap: ".get_home_url()."/sitemap.xml": '' ;
				}
				$robot_txt_code .=$user_agent_code;
				if(get_option('admin_index') != "off"){
					$robot_txt_code .=$admni_url_code;
				}
				if(get_option('theme_index') != "off"){
					$robot_txt_code .=$theme_url_code;
				}
				if(get_option('plagin_index') != "off"){
					$robot_txt_code .=$plagin_url_code;
				}
				if(get_option('media_index') != "off"){
					$robot_txt_code .=$media_url_code;
				}
				$robot_txt_code .=$disallow_url_code;
				$robot_txt_code .=$host_code;
				$robot_txt_code .=$sitemap_code;
			}

			$robot_txt = fopen(ABSPATH."robots.txt","w");
			if(fwrite($robot_txt, $robot_txt_code)) {
				echo "Generate robots.txt done";
			}else{
				echo "Generate robots.txt erroe";
			}
		}
	}
	
// added nofollow attribute
	if(!function_exists('auto_nofollow')){
		function auto_nofollow($content) {
			if(get_option('add_nofollow') == "on"){
				return stripslashes(wp_rel_nofollow($content));
			}
			return $content;
		}
		add_filter('the_content', 'auto_nofollow');
	}
