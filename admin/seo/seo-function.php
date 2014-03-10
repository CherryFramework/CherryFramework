<?php
// robot.txt generater
	if(!function_exists('robot_txt_generate')){
		function robot_txt_generate(){
			$file_dir = ABSPATH."robots.txt";
			if(get_option('generate_robots') == 'off'){
				if(file_exists($file_dir)){
					unlink($file_dir);
				}
			}else{
				$user_agent_code = "User-agent: Yandex";
				$admni_url_code = "\r\nDisallow: /wp-admin\r\nDisallow: /wp-includes\r\nDisallow: /wp-login.php\r\nDisallow: /wp-register.php";
				$theme_url_code = "\r\nDisallow: /wp-content/themes";
				$plagin_url_code = "\r\nDisallow: /wp-content/plugins";
				$media_url_code = "\r\nDisallow: /wp-content/uploads";

				$disallow_url_code = "\r\nDisallow: /wp-content/upgrade";
				$disallow_url_code .= "\r\nDisallow: /wp-content/themes_backup";
				$disallow_url_code .= "\r\nDisallow: /wp-content/cache";
				$disallow_url_code .= "\r\nDisallow: /xmlrpc.php";
				$disallow_url_code .= "\r\nDisallow: /template.html";
				$disallow_url_code .= "\r\nDisallow: /wp-comments";
				$disallow_url_code .= "\r\nDisallow: /cgi-bin";
				$disallow_url_code .= "\r\nDisallow: /trackback";
				$disallow_url_code .= "\r\nDisallow: /feed";
				$disallow_url_code .= "\r\nDisallow: /comments";
				$disallow_url_code .= "\r\nDisallow: /comment-page";
				$disallow_url_code .= "\r\nDisallow: /replytocom=";
				$disallow_url_code .= "\r\nDisallow: /author";
				$disallow_url_code .= "\r\nDisallow: /?author=";
				$disallow_url_code .= "\r\nDisallow: /tag";
				$disallow_url_code .= "\r\nDisallow: /?feed=";
				$disallow_url_code .= "\r\nDisallow: /?s=";
				$disallow_url_code .= "\r\nDisallow: /?se=\r\n";

				$host_code = "\r\nHost: ".$_SERVER["SERVER_NAME"];
				$sitemap_code = "\r\n";
				$robot_txt_code = "";

				for ($i=0; $i < 2; $i++) {
					if($i==1){
						$user_agent_code = "\r\nUser-agent: *";
						$sitemap_code = (get_option('sitemap_done')=="true") ? "\r\nSitemap: ".get_home_url()."/sitemap.xml": '' ;
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
				}
				$robot_txt_code .=$host_code;
				$robot_txt_code .=$sitemap_code;

				$robot_txt = fopen($file_dir,"w");
				if(fwrite($robot_txt, $robot_txt_code)) {
					echo "Generate robots.txt done";
				}else{
					echo "Generate robots.txt erroe";
				}
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
