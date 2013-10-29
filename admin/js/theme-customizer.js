/**
*
* Theme Customizer for CherryFramework.
* Contains handlers to make Theme Customizer preview reload changes asynchronously.
* v1.0.0
*
**/

(function($){
	// Site title and description
	wp.customize('blogname', function(value){
		value.bind(function(to) {
			$('.logo_link').text(to);
		});
	});
	wp.customize('blogdescription', function(value){
		value.bind(function(to) {
			$('.logo_tagline').text(to);
		});
	});
	// Body background-color
	wp.customize(CURRENT_THEME+'[body_background][color]', function(value){
		value.bind(function(to){
			$('body').css({'background-color': to});
		});
	});
	// Layout
	wp.customize(CURRENT_THEME+'[main_layout]', function(value){
		value.bind(function(to){
			if ('fullwidth' === to) {
				$('body').removeClass('cherry-fixed-layout');
			} else{
				$('body').addClass('cherry-fixed-layout');
			};
		});
	});
	// Main background-color
	wp.customize(CURRENT_THEME+'[main_background]', function(value){
		value.bind(function(to){
			$('.cherry-fixed-layout .main-holder').css({'background': to});
		});
	});
	// Header color
	wp.customize(CURRENT_THEME+'[header_background][color]', function(value){
		value.bind(function(to){
			$('.header').css({'background-color': to});
		});
	});
	// Links color
	wp.customize(CURRENT_THEME+'[links_color]', function(value){
		value.bind(function(to){
			$('a').css({'color': to});
		});
	});
	// Breadcrumbs
	wp.customize(CURRENT_THEME+'[g_breadcrumbs_id]', function(value){
		value.bind(function(to){
			if ('no' === to) {
				$('.breadcrumb').css({'display' : 'none'});
			} else{
				$('.breadcrumb').css({'display' : 'block'});
			};
		});
	});
	// Search Box
	wp.customize(CURRENT_THEME+'[g_search_box_id]', function(value){
		value.bind(function(to){
			if ('no' === to) {
				$('.search-form__h').css({'display' : 'none'});
			} else{
				$('.search-form__h').css({'display' : 'block'});
			};
		});
	});
	// Logo-text color
	wp.customize(CURRENT_THEME+'[logo_typography][color]', function(value){
		value.bind(function(to){
			$('.logo_h__txt, .logo_link').css({'color': to});
		});
	});
	// Header Menu Color
	wp.customize(CURRENT_THEME+'[menu_typography][color]', function(value){
		value.bind(function(to){
			$('.sf-menu > li > a').css({'color': to});
		});
	});
	// Footer Menu Color
	wp.customize(CURRENT_THEME+'[footer_menu_typography][color]', function(value){
		value.bind(function(to){
			$('.nav.footer-nav a').css({'color': to});
		});
	});
	// Blog text
	wp.customize(CURRENT_THEME+'[blog_text]', function(value){
		value.bind(function(to) {
			$('.blog .title-header').text(to);
		});
	});
	// Blog text
	wp.customize(CURRENT_THEME+'[blog_related]', function(value){
		value.bind(function(to) {
			$('.related-posts_h').text(to);
		});
	});
	// Blog button text
	wp.customize(CURRENT_THEME+'[blog_button_text]', function(value){
		value.bind(function(to) {
			$('.blog #content .btn').text(to);
		});
	});
	// Portfolio button text
	wp.customize(CURRENT_THEME+'[folio_button_text]', function(value){
		value.bind(function(to) {
			$('#portfolio-grid .btn').text(to);
		});
	});
	// Footer text
	wp.customize(CURRENT_THEME+'[footer_text]', function(value){
		value.bind(function(to) {
			$('#footer-text').text(to);
		});
	});
})(jQuery);