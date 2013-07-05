// ---------------------------------------------------------
// !!!!!!!!!!!!!!!!!document ready!!!!!!!!!!!!!!!!!!!!!!!!!!
// ---------------------------------------------------------
$(document).ready(function(){
// ---------------------------------------------------------
// Prettyphoto
// ---------------------------------------------------------
	var viewportWidth = $('body').innerWidth();
	$("a[rel^='prettyPhoto']").prettyPhoto({
		overlay_gallery: true,
		theme: 'pp_default',
		social_tools: false,
	 	changepicturecallback: function(){
			// 767px is presumed here to be the widest mobile device. Adjust at will.
			if (viewportWidth < 767) {
			   $(".pp_pic_holder.pp_default").css("top",window.pageYOffset+"px");
			}
		}
	});
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
	$(window).bind('resize', img_loader).bind('scroll', img_loader).trigger('scroll');
	function img_loader(){
		var get_img = $('img[data-src]').eq(0)
		if(get_img[0]){
			var visible_height = $(window).scrollTop() + $(window).height(),
				img_top_position = get_img.offset().top, 
				img_src = get_img.attr('data-src');

			if(img_top_position<visible_height){
				get_img.fadeOut(0).attr({'src':img_src}).removeAttr('data-src').bind('load', img_load_complete);
			};
		}else{
			$(window).unbind('resize', img_loader).unbind('scroll', img_loader);
		}
	}
	function img_load_complete(){
		$(this).unbind('load').fadeIn(500)
		img_loader();
	}
});