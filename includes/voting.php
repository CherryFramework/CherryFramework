<?php 
//------------------------------------------------------
//  set voting post
//------------------------------------------------------
	if (isset($_GET["post_ID"])){ 
	        $post_ID = $_GET["post_ID"];
	}
	if (isset($_GET["get_user_ID"])){ 
	        $get_user_ID = $_GET["get_user_ID"];
	}
	if (isset($_GET["voting"])){ 
	        $voting = $_GET["voting"];
	}
	include_once ('../../../../wp-load.php');

	$user_like_array = !in_array($get_user_ID, get_post_meta($post_ID, 'user_like'));
	$user_dislike_array = !in_array($get_user_ID, get_post_meta($post_ID, 'user_dislike'));
	$users = get_users();

    foreach ($users as $user) {
    	if(($user->ID == $get_user_ID)){
        	$security_user = true;
        	break;
	    }else{
	    	$security_user = false;
	    }
    }

    if($security_user && $user_like_array && $user_dislike_array){
    	$count = get_post_meta($post_ID, 'post_'.$voting , true)+1;
		update_post_meta($post_ID, 'post_'. $voting, $count);
		add_post_meta($post_ID, 'user_'.$voting, $get_user_ID);
    }else{
    	echo theme_locals('have_voting');
    }
?>