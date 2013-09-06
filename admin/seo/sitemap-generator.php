<?php
	if(!function_exists('generate_sitemap')){
		function generate_sitemap(){
			global $site_link;
			$main_changefreq = 'monthly';
			$main_priority = '1,0';
			$site_link = get_home_url().'/';
			$get_recent_posts =  wp_get_recent_posts(array('numberposts' => 1, 'post_type' => 'any', 'post_status'=>array('publish', 'private')));
			$lastmod = get_option('page_on_front') !=0 ? get_post(get_option('page_on_front')) -> post_modified : $get_recent_posts[0]['post_modified'];
			$lastmod_xml = ($lastmod != null) ? "\r\n\t\t\t<lastmod>".$lastmod."</lastmod>" : '';
			$sitemap_code = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\r\n\t<urlset xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xsi:schemaLocation=\"http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd\" xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\r\n\t\t<url>\r\n\t\t\t<loc>".$site_link."</loc>".$lastmod_xml."\r\n\t\t\t<changefreq>".$main_changefreq."</changefreq>\r\n\t\t\t<priority>".$main_priority."</priority>\r\n\t\t</url>";

			$args=array('public' => true, '_builtin' => false); 
			$post_types=get_post_types($args,'names', 'or'); 
			$sort_array =array('page' => '', 'post' => '', 'services' => '', 'portfolio' => '', 'slider' => '', 'team' => '', 'testi' => '', 'faq' => '');
			$post_types = array_merge($sort_array, $post_types);
			unset($post_types['optionsframework'], $post_types['wpcf7_contact_form']);

			query_posts(array('post_type' => $post_types, 'posts_per_page' => '-1'));
			if ( have_posts() ) while( have_posts() )  {
				the_post();
				if(isset($_POST['checked_'.get_post_type()])){
					$post_changefreq = isset($_POST['changefreq_'.get_post_type()]) ? "\r\n\t\t\t<changefreq>".$_POST['changefreq_'.get_post_type()]."</changefreq>": "";
					$post_priority = isset($_POST['priority_'.get_post_type()]) ? "\r\n\t\t\t<priority>".$_POST['priority_'.get_post_type()]."</priority>": "";

					$sitemap_code .= "\r\n\t\t<url>\r\n\t\t\t<loc>".get_permalink()."</loc>\r\n\t\t\t<lastmod>".get_the_modified_date('Y-m-d')."</lastmod>".$post_changefreq.$post_priority."\r\n\t\t</url>";
				}
			}
			wp_reset_query();
			$sitemap_code .="\r\n</urlset>";

			$sitemap = fopen(ABSPATH."sitemap.xml","w");
			if(fwrite($sitemap, $sitemap_code)) {
				ping_search_system();
				update_option('sitemap_done', 'true');
				echo "Generate sitemap.xml done";
			}else{
				update_option('sitemap_done', 'false');
				echo "Generate sitemap.xml erroe";
			}
			exit;
		}
		add_action('wp_ajax_generate_sitemap' ,'generate_sitemap');
	}

	if(!function_exists('ping_search_system')){
		function ping_search_system(){
			global $site_link;
			$url ='';
			$last_ping = (get_option('last_ping_search_system')!=false) ? get_option('last_ping_search_system') : 0;
			$limit_time = (time() - $last_ping > 3600); //limit time 3600 sm - 1 hour;

			if(count($_POST)>1 && $limit_time){
				foreach ($_POST as $key) {
					switch ($key) {
						case 'google_ping':
							$url = 'http://google.com/webmasters/sitemaps/ping?sitemap=';
							break;
						case 'yandex_ping':
							$url = 'http://webmaster.yandex.ru/wmconsole/sitemap_list.xml?host=';
							break;
						case 'yahoo_ping':
							$url = 'http://search.yahooapis.com/SiteExplorerService/V1/ping?sitemap=';
							break;
						case 'bing_ping':
							$url = 'http://www.bing.com/webmaster/ping.aspx?siteMap=';
							break;
						case 'ask_ping':
							$url = 'http://submissions.ask.com/ping?sitemap=';
							break;
					}
					if(@get_headers($url)){
						wp_remote_get( $url.$site_link."sitemap.xml" );
					}
				}
				update_option('last_ping_search_system', time());
			}
		}
	}
?>