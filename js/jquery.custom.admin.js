var hide_options;
jQuery(document).ready(function() {

/*----------------------------------------------------------------------------------*/
/*	Portfolio Custom Fields Hide/Show
/*----------------------------------------------------------------------------------*/
	var portfolioTypeTrigger = jQuery('#tz_portfolio_type'),
		portfolioImage = jQuery('#tz-meta-box-portfolio-image'),
		portfolioVideo = jQuery('#tz-meta-box-portfolio-video'),
		portfolioAudio = jQuery('#tz-meta-box-portfolio-audio');
		currentType = portfolioTypeTrigger.val();
		
	tzSwitchPortfolio(currentType);

	portfolioTypeTrigger.change( function() {
	   currentType = jQuery(this).val();
	   
	   tzSwitchPortfolio(currentType);
	});
	
	function tzSwitchPortfolio(currentType) {
		if( currentType === 'Audio' ) {
			tzHideAllPortfolio(portfolioAudio);
		} else if( currentType === 'Video' ) {
			tzHideAllPortfolio(portfolioVideo);
		} else {
			tzHideAllPortfolio(portfolioImage);
		}
	}
	
	function tzHideAllPortfolio(notThisOne) {
		portfolioImage.css('display', 'none');
		portfolioVideo.css('display', 'none');
		portfolioAudio.css('display', 'none');
		notThisOne.css('display', 'block');
	}


// ---------------------------------------------------------
//  	Quote
// ---------------------------------------------------------
	var quoteOptions = jQuery('#tz-meta-box-quote');
	var quoteTrigger = jQuery('#post-format-quote');
	
	quoteOptions.css('display', 'none');

// ---------------------------------------------------------
//  	Image
// ---------------------------------------------------------
	var imageOptions = jQuery('#tz-meta-box-image');
	var imageTrigger = jQuery('#post-format-image');
	
	imageOptions.css('display', 'none');

// ---------------------------------------------------------
//  	Link
// ---------------------------------------------------------
	var linkOptions = jQuery('#tz-meta-box-link');
	var linkTrigger = jQuery('#post-format-link');
	
	linkOptions.css('display', 'none');
	
// ---------------------------------------------------------
//  	Audio
// ---------------------------------------------------------	
	var audioOptions = jQuery('#tz-meta-box-audio');
	var audioTrigger = jQuery('#post-format-audio');
	
	audioOptions.css('display', 'none');
	
// ---------------------------------------------------------
//  	Video
// ---------------------------------------------------------
	var videoOptions = jQuery('#tz-meta-box-video');
	var videoTrigger = jQuery('#post-format-video');
	
	videoOptions.css('display', 'none');


// ---------------------------------------------------------
//  	Core
// ---------------------------------------------------------
	var group = jQuery('#post-formats-select input');

	
	group.change( function() {
		
		if(jQuery(this).val() == 'quote') {
			quoteOptions.css('display', 'block');
			tzHideAll(quoteOptions);
			
		} else if(jQuery(this).val() == 'link') {
			linkOptions.css('display', 'block');
			tzHideAll(linkOptions);
			
		} else if(jQuery(this).val() == 'audio') {
			audioOptions.css('display', 'block');
			tzHideAll(audioOptions);
			
		} else if(jQuery(this).val() == 'video') {
			videoOptions.css('display', 'block');
			tzHideAll(videoOptions);
			
		} else if(jQuery(this).val() == 'image') {
			imageOptions.css('display', 'block');
			tzHideAll(imageOptions);
			
		} else {
			quoteOptions.css('display', 'none');
			videoOptions.css('display', 'none');
			linkOptions.css('display', 'none');
			audioOptions.css('display', 'none');
			imageOptions.css('display', 'none');
		}
		
	});
	
	if(quoteTrigger.is(':checked'))
		quoteOptions.css('display', 'block');
		
	if(linkTrigger.is(':checked'))
		linkOptions.css('display', 'block');
		
	if(audioTrigger.is(':checked'))
		audioOptions.css('display', 'block');
		
	if(videoTrigger.is(':checked'))
		videoOptions.css('display', 'block');
		
	if(imageTrigger.is(':checked'))
		imageOptions.css('display', 'block');
		
	function tzHideAll(notThisOne) {
		videoOptions.css('display', 'none');
		quoteOptions.css('display', 'none');
		linkOptions.css('display', 'none');
		audioOptions.css('display', 'none');
		imageOptions.css('display', 'none');
		notThisOne.css('display', 'block');
	}

/*----------------------------------------------------------------------------------*/
/*	Page Category Include Fields Hide/Show
/*----------------------------------------------------------------------------------*/
	var currentTemplate = jQuery('#page_template').val();
	if( (currentTemplate == 'page-Portfolio2Cols-filterable.php') 
		|| (currentTemplate == 'page-Portfolio3Cols-filterable.php') 
		|| (currentTemplate == 'page-Portfolio4Cols-filterable.php') ) {
		// show the meta box
		jQuery('#tz-meta-box-category').show();
	} else {
		// hide your meta box
		jQuery('#tz-meta-box-category').hide();
	}
	jQuery('#page_template').live('change', function(){
		currentTemplate = jQuery(this).val();
		if( (currentTemplate == 'page-Portfolio2Cols-filterable.php') 
			|| (currentTemplate == 'page-Portfolio3Cols-filterable.php') 
			|| (currentTemplate == 'page-Portfolio4Cols-filterable.php') ) {
			// show the meta box
			jQuery('#tz-meta-box-category').show();
		} else {
			// hide your meta box
			jQuery('#tz-meta-box-category').hide();
		}
	});

//--------------------------------------------
// Shotcode
//--------------------------------------------
		//icon shotcode
		jQuery("#framework-icon_type").live("change", function(){
			hide_options(jQuery(this).val(), jQuery(this));
		})
		hide_options = function (val, element){
			var show_element = val.toLocaleLowerCase().replace(" ", "_"),
				options_parent = element.parents("#options-table");

			if(show_element=="images"){
				jQuery(".tupe_font_icon", options_parent).parents("tr").css({"display":"none"});
				jQuery(".tupe_images", options_parent).parents("tr").css({"display":"table-row"});
			}else{
				jQuery(".tupe_images", options_parent).parents("tr").css({"display":"none"});
				jQuery(".tupe_font_icon", options_parent).parents("tr").css({"display":"table-row"});
			}
			
			
		}
//--------------------------------------------
// backup
//--------------------------------------------
	var button_stule = "display:block; position:absolute; left:50%; margin-left:-12px; top:-5px;";

	jQuery(".backup_theme").click(function(){
		var backup_button = jQuery(this);
		backup_button.css({'visibility':'hidden'}).parent().css({'position':'relative'}).append('<span class="spinner backup_spinner" style="'+button_stule+'"></span>');
		jQuery.ajax({
			url: backup_button.attr("href"),
			type: "POST",
			success: function(){
				jQuery(backup_button).next(".spinner").css({'background':'url("images/yes.png") center no-repeat'});
				setTimeout(function(){
					jQuery(backup_button).css({'visibility':'visible'}).next(".spinner").remove()
				}, 5000)
			}
		});
		return false;
	})
});
