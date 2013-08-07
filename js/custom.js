// ---------------------------------------------------------
// Magnific Popup Init
// ---------------------------------------------------------
function magnific_popup_init(item) {
	item.magnificPopup({
		delegate: 'a[rel^="prettyPhoto"]',
		type: 'image',
		removalDelay: 500,
		mainClass: 'mfp-zoom-in',
		callbacks: {
			beforeOpen: function() {
				// just a hack that adds mfp-anim class to markup 
				this.st.image.markup = this.st.image.markup.replace('mfp-figure', 'mfp-figure mfp-with-anim');
			}
		},
		gallery: {enabled:true}
	});
}
// ---------------------------------------------------------
// !!!!!!!!!!!!!!!!!document ready!!!!!!!!!!!!!!!!!!!!!!!!!!
// ---------------------------------------------------------
$(document).ready(function(){
// ---------------------------------------------------------
// Call Magnific Popup
// ---------------------------------------------------------
	$(".thumbnail").parent().each(function(){magnific_popup_init($(this))});
// ---------------------------------------------------------
// Tooltip
// ---------------------------------------------------------
	$("[rel='tooltip']").tooltip();
// ---------------------------------------------------------
// Back to Top
// ---------------------------------------------------------
	$(window).scroll(function () {
		if ($(this).scrollTop() > 100) {
			$('#back-top').fadeIn();
		} else {
			$('#back-top').fadeOut();
		}
	});
	$('#back-top a').click(function () {
		$('body,html').stop(false, false).animate({
			scrollTop: 0
		}, 800);
		return false;
	});
// ---------------------------------------------------------
// Add accordion active class
// ---------------------------------------------------------
	$('.accordion').on('show', function (e) {
		$(e.target).prev('.accordion-heading').find('.accordion-toggle').addClass('active');
	});
	$('.accordion').on('hide', function (e) {
		$(this).find('.accordion-toggle').not($(e.target)).removeClass('active');
	});
// ---------------------------------------------------------
// Isotope Init
// ---------------------------------------------------------
	$("#portfolio-grid").css({"visibility" : "visible"});
// ---------------------------------------------------------
// Menu Android
// ---------------------------------------------------------
	if(window.orientation!=undefined){
		var regM = /ipod|ipad|iphone/gi,
			result = navigator.userAgent.match(regM)
		if(!result) {
			$('.sf-menu li').each(function(){
				if($(">ul", this)[0]){
					$(">a", this).toggle(
						function(){
							return false;
						},
						function(){
							window.location.href = $(this).attr("href");
						}
					);
				} 
			})
		}
	}
// ---------------------------------------------------------
// images loader
// ---------------------------------------------------------
	var MSIE8 = ($.browser.msie) && ($.browser.version == 8);
	$('img[data-src]').bind('load', img_load_complete);
	$(window).bind('resize', img_loader).bind('scroll', img_loader).trigger('scroll');
	
	function img_loader(){
		var get_img = $('img[data-src]').eq(0)
		if(get_img[0]){
			var visible_height = $(window).scrollTop() + $(window).height(),
				img_top_position = get_img.offset().top;

			if(img_top_position<visible_height){
				get_img.attr({'src':get_img.attr('data-src')}).removeAttr('data-src');
				if(!MSIE8){
					get_img.fadeOut(0)
				}
			};
		}else{
			$(window).unbind('resize', img_loader).unbind('scroll', img_loader);
		}
	}
	function img_load_complete(){
		$(this).unbind('load');
		if(!MSIE8){
			$(this).fadeIn(500)
		}
		img_loader();
	}
// ---------------------------------------------------------
// set voting post JS
// ---------------------------------------------------------
	$('.ajax_voting').bind('click', voitng);
	function voitng(){
		var item= $(this),
			item_parent = item.parents('[class*="meta_type"]'),
			type = item.attr('date-type'),
			item_class='user_'+type,
			count = parseInt($('.voting_count', item).text()),
			top_position = (type==='like') ? -18 : 18 ,
			mark = (type==='like') ? '+' : '-', 
			post_url = item.attr('href');

		$('.post_like>a, .post_dislike>a', item_parent).unbind('click', voitng).removeAttr('href date-type').removeClass('ajax_voting').addClass('user_voting');
		item.removeClass('user_voting').addClass(item_class).find('.voting_count').text(++count).append('<span class="animation_item">'+mark+'1</span>');
		$('.animation_item', item).stop(true).animate({'top':top_position, opacity:'0'}, 500, 'easeOutCubic', function(){$(this).remove()});

		$.post(post_url);
		return false;
	}
});