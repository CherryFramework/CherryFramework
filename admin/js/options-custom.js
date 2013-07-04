/**
 * Prints out the inline javascript needed for the colorpicker and choosing
 * the tabs in the panel.
 */

jQuery(document).ready(function($) {
	
	// Fade out the save message
	$('.fade').delay(1000).fadeOut(1000);
	
	// Color Picker
	$('.colorSelector').each(function(){
		var Othis = this; //cache a copy of the this variable for use inside nested function
		var initialColor = $(Othis).next('input').attr('value');
		$(this).ColorPicker({
			color: initialColor,
			onShow: function (colpkr) {
				$(colpkr).fadeIn(0);
				return false;
			},
			onHide: function (colpkr) {
				$(colpkr).fadeOut(0);
				return false;
			},
			onChange: function (hsb, hex, rgb) {
				$(Othis).children('div').css('backgroundColor', '#' + hex);
				$(Othis).next('input').attr('value','#' + hex);
			}
		});
	}); //end color picker
	
	// Switches option sections
	$('.group').hide(0);
	var activetab = '';

	activetab = window.location.hash;

	if (activetab != '' && $(activetab).length ) {
		$(activetab).fadeIn(0);
	} else {
		$('.group:first').fadeIn(0);
	}
	$('.group .collapsed').each(function(){
		$(this).find('input:checked').parent().parent().parent().nextAll().each( 
			function(){
				if ($(this).hasClass('last')) {
					$(this).removeClass('hidden');
						return false;
					}
				$(this).filter('.hidden').removeClass('hidden');
			});
	});
	
	if (activetab != '' && $(activetab + '-tab').length ) {
		$(activetab + '-tab').addClass('nav-tab-active');
	}
	else {
		$('.nav-tab-wrapper a:first').addClass('nav-tab-active');
	}
	$('.nav-tab-wrapper a').click(function(evt) {
		var clicked_group = $(this).attr('href');

		if(clicked_group!=window.location.hash){
			$('.nav-tab-wrapper a').removeClass('nav-tab-active');
			$(this).addClass('nav-tab-active').blur();
			
			window.location.hash = clicked_group;
			$('.group').hide(0);
			$(clicked_group).fadeIn(0);
		}
		evt.preventDefault();
		
		// Editor Height (needs improvement)
		$('.wp-editor-wrap').each(function() {
			var editor_iframe = $(this).find('iframe');
			if ( editor_iframe.height() < 30 ) {
				editor_iframe.css({'height':'auto'});
			}
		});
	
	});
           					
	$('.group .collapsed input:checkbox').click(unhideHidden);
				
	function unhideHidden(){
		if ($(this).attr('checked')) {
			$(this).parent().parent().parent().nextAll().removeClass('hidden');
		}
		else {
			$(this).parent().parent().parent().nextAll().each( 
			function(){
				if ($(this).filter('.last').length) {
					$(this).addClass('hidden');
					return false;		
					}
				$(this).addClass('hidden');
			});  					
		}
	}
	
	// Image Options
	$('.of-radio-img-img').click(function(){
		$(this).parent().parent().find('.of-radio-img-img').removeClass('of-radio-img-selected');
		$(this).addClass('of-radio-img-selected');		
	});
		
	$('.of-radio-img-label').hide(0);
	$('.of-radio-img-img').show(0);
	$('.of-radio-img-radio').hide(0);


	// Show/Hide Portfolio excerpt field
	$('#section-folio_excerpt input[type="radio"]').click(function() {
		if ($(this).filter(":checked").val() == 'yes'){
	  		$('#section-folio_excerpt_count').fadeIn(0);
		} else {
			$('#section-folio_excerpt_count').fadeOut(0);
		};		
	});	

	if ($('#section-folio_excerpt input[type="radio"]:checked').val() == 'no') {
		$('#section-folio_excerpt_count').hide();
	}


	// Show/Hide Logo Typography
	$('#section-logo_type input[type="radio"]').click(function() {
		if ($(this).filter(":checked").val() == 'text_logo'){
	  		$('#section-logo_typography').show(0);
	  		$('#section-logo_url').hide(0);
		} else {
			$('#section-logo_typography').hide(0);
			$('#section-logo_url').show(0);
		};		
	});	

	if ($('#section-logo_type input[type="radio"]:checked').val() == 'image_logo') {
		$('#section-logo_typography').hide(0);
		$('#section-logo_url').show(0);
	}

	// Show/Hide Footer Menu Typography
	$('#section-footer_menu input[type="radio"]').click(function() {
		if ($(this).filter(":checked").val() == 'true'){
	  		$('#section-footer_menu_typography').fadeIn(0);
		} else {
			$('#section-footer_menu_typography').fadeOut(0);
		};		
	});	

	if ($('#section-footer_menu input[type="radio"]:checked').val() == 'false') {
		$('#section-footer_menu_typography').hide(0);
	}

	// Show/Hide Slider options
	$('#section-slider_type .of-radio-img-img').click(change_slider_type);
	change_slider_type();
	function change_slider_type(){
		if ($("#slider_type_camera_slider").attr("checked") == "checked"){
			$(".slider_type_1").css({"display":"block"});
	  		$(".slider_type_2").css({"display":"none"});
		} else if($("#slider_type_accordion_slider").attr("checked") == "checked"){
			$(".slider_type_1").css({"display":"none"});
			$(".slider_type_2").css({"display":"block"});
		} else if($("#slider_type_none_slider").attr("checked") == "checked"){
			$(".slider_type_1").css({"display":"none"});
			$(".slider_type_2").css({"display":"none"});
		}
	}
});