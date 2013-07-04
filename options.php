<?php 
if(!function_exists('optionsframework_options')){
	function optionsframework_options(){
	// Fonts
		global $typography_mixed_fonts;
		$typography_mixed_fonts = array_merge(options_typography_get_os_fonts() , options_typography_get_google_fonts());
		asort($typography_mixed_fonts);
		return array();
	}
}?>