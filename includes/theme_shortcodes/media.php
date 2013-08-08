<?php
// Audio Player
if (!function_exists('shortcode_audio')) {

	function shortcode_audio( $atts, $content = null ) {
		extract(shortcode_atts(array(
			'type'  => '',
			'file'  => '',
			'title' => ''
		), $atts));

		$template_url = get_template_directory_uri();
		$id = rand();

		if ( empty($file) ) {
			$audio_array = array(
				'mpeg'  => '', 
				'mp3'   => '', 
				'mp4'   => '', 
				'm4a'   => '', 
				'ogg'   => '', 
				'oga'   => '', 
				'webm'  => '', 
				'webma' => '', 
				'wav'   => ''
				);

			$result = array_intersect_key($atts, $audio_array);

			if ( !empty($result) ) {
				foreach ($result as $key => $value) {
					$type = $key;
					$file = $value;
				}
			} else
				return;
		}

		// get audio attribute
		$content_url = content_url();
		$content_str = 'wp-content';

		$pos = strpos($file, $content_str);
		if ($pos !== false) {
			$audio_new = substr($file, $pos+strlen($content_str), strlen($file) - $pos);
			$file      = $content_url.$audio_new;
		}

		$output = '<div class="audio-wrap">';
		$output .= '<script type="text/javascript">
						jQuery(document).ready(function(){
							if(jQuery().jPlayer) {
								jQuery("#jquery_jplayer_'. $id .'").jPlayer( {
									ready: function () {
										jQuery(this).jPlayer("setMedia", {'.
											$type .': "'. $file .'",
											end: ""
										});
									},
									play: function() {
										jQuery(this).jPlayer("pauseOthers");
									},
									swfPath: "'. $template_url .'/flash",
									wmode: "window",
									cssSelectorAncestor: "#jp_container_'. $id .'",
									supplied: "'. $type .',  all"
								});
							}
						});
					</script>';

		$output .= '<div id="jquery_jplayer_'. $id .'" class="jp-jplayer"></div>';
		$output .= '<div id="jp_container_'. $id .'" class="jp-audio">';
		$output .= '<div class="jp-type-single">';
		$output .= '<div class="jp-gui">';
		$output .= '<div class="jp-interface">';
		$output .= '<div class="jp-progress">';
		$output .= '<div class="jp-seek-bar"><div class="jp-play-bar"></div></div>';
		$output .= '</div>';
		$output .= '<div class="jp-duration"></div><div class="jp-time-sep"></div><div class="jp-current-time"></div>';
		$output .= '<div class="jp-controls-holder">';
		$output .= '<ul class="jp-controls">';
		$output .= '<li><a href="javascript:;" class="jp-play" tabindex="1" title="'.theme_locals("play").'"><span>'.theme_locals("play").'</span></a></li>';
		$output .= '<li><a href="javascript:;" class="jp-pause" tabindex="1" title="'.theme_locals("pause").'"><span>'.theme_locals("pause").'</span></a></li>';
		$output .= '<li><a href="javascript:;" class="jp-stop" tabindex="1" title="'.theme_locals("stop").'"><span>'.theme_locals("stop").'</span></a></li>';
		$output .= '</ul>';
		$output .= '<div class="jp-volume-bar">';
		$output .= '<div class="jp-volume-bar-value">';
		$output .= '</div></div>';
		$output .= '<ul class="jp-toggles">';
		$output .= '<li><a href="javascript:;" class="jp-mute" tabindex="1" title="'.theme_locals("mute").'"><span>'.theme_locals("mute").'</span></a></li>';
		$output .= '<li><a href="javascript:;" class="jp-unmute" tabindex="1" title="'.theme_locals("unmute").'""><span>'.theme_locals("unmute").'</span></a></li>';
		$output .= '</ul>';
		$output .= '<div class="jp-title"><ul><li>'. $title .'</li></ul></div></div>';
		$output .= '</div>';
		$output .= '<div class="jp-no-solution">';
		$output .= theme_locals("update_required");
		$output .= '</div></div></div></div>';
		$output .= '</div><!-- .audio-wrap (end) -->';

		return $output;
	}
	add_shortcode('audio', 'shortcode_audio');
}


// Video Player
if (!function_exists('wp_video_shortcode')) {
	if (!function_exists('shortcode_video')) {

		function shortcode_video( $atts, $content = null ) {
			extract(shortcode_atts(array(
				'file'   => '',
				'm4v'    => '',
				'ogv'    => '',
				'width'  => '600',
				'height' => '350',
			), $atts));

			$template_url = get_template_directory_uri();
			$id = rand();

			$video_url = $file;
			$m4v_url   = $m4v;
			$ogv_url   = $ogv;

			// get content URL
			$content_url = content_url();
			$content_str = 'wp-content';

			$pos1     = strpos($m4v_url, $content_str);
			if ($pos1 === false) {
				$file1 = $m4v_url;
			} else {
				$m4v_new  = substr($m4v_url, $pos1+strlen($content_str), strlen($m4v_url) - $pos1);
				$file1    = $content_url.$m4v_new;
			}

			$pos2     = strpos($ogv_url, $content_str);
			if ($pos2 === false) {
				$file2 = $ogv_url;
			} else {
				$ogv_new  = substr($ogv_url, $pos2+strlen($content_str), strlen($ogv_url) - $pos2);
				$file2    = $content_url.$ogv_new;
			}

			//Check for video format
			$vimeo   = strpos($video_url, "vimeo");
			$youtube = strpos($video_url, "youtu");

			$output = '<div class="video-wrap">';

			//Display video
			if ($file) {
				if($vimeo !== false){

				//Get ID from video url
				$video_id = str_replace( 'http://vimeo.com/', '', $video_url );
				$video_id = str_replace( 'http://www.vimeo.com/', '', $video_id );

				//Display Vimeo video
				$output .= '<iframe src="http://player.vimeo.com/video/'.$video_id.'?title=0&amp;byline=0&amp;portrait=0" width="'.$width.'" height="'.$height.'" frameborder="0"></iframe>';

				} elseif($youtube !== false){

				//Get ID from video url
				$video_id = str_replace( 'http://', '', $video_url );
				$video_id = str_replace( 'https://', '', $video_id );
				$video_id = str_replace( 'www.youtube.com/watch?v=', '', $video_id );
				$video_id = str_replace( 'youtube.com/watch?v=', '', $video_id );
				$video_id = str_replace( 'youtu.be/', '', $video_id );
				$video_id = str_replace( '&feature=channel', '', $video_id );

				$output .= '<iframe title="YouTube video player" class="youtube-player" type="text/html" width="'.$width.'" height="'.$height.'" src="http://www.youtube.com/embed/'.$video_id.'" frameborder="0"></iframe>';

				}
			} else {

				$output .= '<script type="text/javascript">
							jQuery(document).ready(function(){
								if(jQuery().jPlayer) {
									jQuery("#jquery_jplayer_'. $id .'").jPlayer( {
										ready: function () {
											jQuery(this).jPlayer("setMedia", {
												m4v: "'. $file1 .'",
												ogv: "'. $file2 .'"
											});
										},
										play: function() {
											jQuery(this).jPlayer("pauseOthers");
										},
										swfPath: "'. $template_url .'/flash",
										wmode: "window",
										cssSelectorAncestor: "#jp_container_'. $id .'",
										solution: "flash, html",
										supplied: "ogv, m4v, all",
										size: {width: "100%", height: "100%"}
									});
								}
							});
						</script>';
				$output .= '<div id="jp_container_'. $id .'" class="jp-video fullwidth">';
				$output .= '<div class="jp-type-single">';
				$output .= '<div id="jquery_jplayer_'. $id .'" class="jp-jplayer"></div>';
				$output .= '<div class="jp-gui">';
				$output .= '<div class="jp-video-play">';
				$output .= '<a href="javascript:;" class="jp-video-play-icon" tabindex="1" title="'.theme_locals("play").'">'.theme_locals("play").'</a></div>';
				$output .= '<div class="jp-interface">';
				$output .= '<div class="jp-progress">';
				$output .= '<div class="jp-seek-bar">';
				$output .= '<div class="jp-play-bar">';
				$output .= '</div></div></div>';
				$output .= '<div class="jp-duration"></div>';
				$output .= '<div class="jp-time-sep">/</div>';
				$output .= '<div class="jp-current-time"></div>';
				$output .= '<div class="jp-controls-holder">';
				$output .= '<ul class="jp-controls">';
				$output .= '<li><a href="javascript:;" class="jp-play" tabindex="1" title="'.theme_locals("play").'"><span>'.theme_locals("play").'</span></a></li>';
				$output .= '<li><a href="javascript:;" class="jp-pause" tabindex="1" title="'.theme_locals("pause").'"><span>'.theme_locals("pause").'</span></a></li>';
				$output .= '<li class="li-jp-stop"><a href="javascript:;" class="jp-stop" tabindex="1" title="'.theme_locals("stop").'"><span>'.theme_locals("stop").'</span></a></li>';
				$output .= '</ul>';
				$output .= '<div class="jp-volume-bar">';
				$output .= '<div class="jp-volume-bar-value">';
				$output .= '</div></div>';
				$output .= '<ul class="jp-toggles">';
				$output .= '<li><a href="javascript:;" class="jp-mute" tabindex="1" title="'.theme_locals("mute").'"><span>'.theme_locals("mute").'</span></a></li>';
				$output .= '<li><a href="javascript:;" class="jp-unmute" tabindex="1" title="'.theme_locals("unmute").'"><span>'.theme_locals("unmute").'</span></a></li>';
				$output .= '</ul>';
				$output .= '</div></div>';
				$output .= '<div class="jp-no-solution">';
				$output .= theme_locals("update_required");
				$output .= '</div></div></div></div>'; 

			}
			$output .= '</div><!-- .video-wrap (end) -->';
			return $output;
		}
		add_shortcode('video', 'shortcode_video');
	}
}

// Video Preview
if (!function_exists('shortcode_video_preview')) {
		function shortcode_video_preview($atts, $content = null) {
			extract(shortcode_atts(
		array(
			'title'        => '',
			'post_url'     => '',
			'date'         => '',
			'author'       => '',
			'custom_class' => '',
		), $atts));
	   $output_title = '';
	   $output_author = '';
	   $output_date = '';
	   $post_ID = url_to_postid($post_url);
	   $get_post = get_post($post_ID);
	   $get_user = get_userdata($get_post->post_author);
	   $user_url = get_bloginfo('url').'/author/'.$get_user->user_nicename;
	   $video_url = parser_video_url(get_post_meta($post_ID, 'tz_video_embed', true));
	   $get_image_url = video_image($video_url);
	   $img='';

	   if($title=="yes"){
		$output_title = '<h4><a href="'.$post_url.'" title="'.$get_post->post_title.'">'.$get_post->post_title.'</a></h4>';
	   }
	   if($author=="yes"){
		$output_author = '<span class="post_author">Posts by <a href="'.$user_url.'" title="Posts by '.$get_user->user_nicename.'"  rel="author">'.$get_user->user_nicename.'</a></span>';
	   }
	   if($date=="yes"){
		$output_date = '<span class="post_date"><time datetime="'.$get_post->post_date.'"> '.get_the_time('M j, Y', $post_ID).'</time></span>';
	   }
	   if($get_image_url!=false && $get_image_url!=''){
		$img = '<a class="preview_image"  href="'.$post_url.'" title="'.$get_image_url.'"><img src="'.$get_image_url.'" alt=""><span class="icon-play-circle hover"></span></a>';
	   }
	   $output ='<figure class="featured-thumbnail thumbnail video_preview clearfix'.$custom_class.'"><div>'.$img.'<figcaption>'.$output_title.$output_author.$output_date.'</figcaption></div></figure>';

	   return $output;
	  }
	  add_shortcode('video_preview', 'shortcode_video_preview');
	 }
	 if (!function_exists('parser_video_url')) {
	  function parser_video_url($video_url){
	   $video_url = explode(" ", $video_url);
	   foreach ($video_url as $item) {
		if(stripos($item, 'src')!==false){
		 $url_array = parse_url($item);
		 $video_url = $url_array["path"];
		 $video_url = stripcslashes($video_url);
		 $video_url = strip_tags($video_url);
		 $video_url = str_replace('&quot;', '', $video_url);
		 break;
		}
	   }
	   return $video_url;
	  }
	 }
	 if (!function_exists('video_image')) {
	  function video_image($url){
	   if($url[0]!==''){
		$image_id = basename($url);
		if(stripos($url, "youtube")!==false){
		 return "http://img.youtube.com/vi/".$image_id."/0.jpg";
		} else if(stripos($url, "vimeo")!==false){
		 $hash = unserialize(file_get_contents("http://vimeo.com/api/v2/video/".$image_id.".php"));
		 return $hash[0]["thumbnail_large"];
		}
	   }else{
		return false;
	   }
	  }
	 }
?>