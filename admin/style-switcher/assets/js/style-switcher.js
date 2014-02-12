(function($) {
	wp.customize(CURRENT_THEME + '[main_layout]', function(value) {
		value.bind(function(to) {
			var $body = $('body');
			if (to === 'fullwidth') {
				$body.removeClass('cherry-fixed-layout');
			} else if (to === 'fixed') {
				$body.addClass('cherry-fixed-layout');
			};
		})
	});
	wp.customize(CURRENT_THEME + '[color_skin]', function(value) {
		value.bind(function(to) {
			update_option('cherry_color_skin', to);
		})
	});
	wp.customize(CURRENT_THEME + '[links_color]', function(value) {
		value.bind(function(to) {
			update_option('cherry_color_schemes', to);
		})
	});
	wp.customize(CURRENT_THEME + '[slider_type]', function(value) {
		value.bind(function(to) {
			get_slider_template_part(to);
		})
	});
	function update_option(n,v){
		var $data = {
				action: 'custom_update_option',
				option_name: n,
				option_value: v
			},
			$status = $('#style-switcher-status');
		$.ajax({
			type: 'POST',
			url: ajaxurl,
			data: $data,
			beforeSend: function(){
				$status
					.text('saving...')
					.stop()
					.animate({top:0});
			},
			success: function(x,t){
				if (n === 'cherry_color_skin') {
					reload_css(v);
				};
				if (n === 'cherry_color_schemes') {
					refresh_css(v);
				};
				$status
					.text(t)
					.delay(1000)
					.animate({top:-$status.innerHeight()});
			}
		});
	};
	function reload_css(v){
		var css = $('link#cherry-style-switcher-skin-css');
		css[0].href = v;
	};
	function refresh_css(v){
		var query = '?reload=' + new Date().getTime(),
			css = $('link#cherry-style-switcher-schemes-css'),
			i = 0;

		if ( css[i].href.indexOf('main-style.css') > -1) {
			css[i].href = css[i].href.replace('main', 'demo');
		} else {
			css[i].href = css[i].href.replace(/\?.*|$/, query);
		}
	};
	function get_slider_template_part(t){
		var $data = {
				action: 'require_template_part',
				template_part: t
			},
			$status = $('#style-switcher-status');
		if ('none_slider' === t) {
			$('#slider-wrapper')
				.parent()
				.html('<div class="slider_off"></div>');
		} else {
			if ($('#slider-wrapper').length) {
				$slider = $('#slider-wrapper');
			} else{
				$slider = $('.slider_off');
			};
			$.ajax({
				type: 'POST',
				url: ajaxurl,
				data: $data,
				beforeSend: function(){
					$slider.css({'display': 'none'});
					$status
						.text('saving...')
						.stop()
						.animate({top:0});
				},
				success: function(x,t){
					$slider
						.parent()
						.html('<div id="slider-wrapper">' + x + '</div>');
					var imgs = $('.accordion_wrap img');
					if (imgs){
						for (var i = 0; i < imgs.length; i++) {
							img = $('.accordion_wrap img').eq(i);
							img.attr({'src':img.attr('data-src')}).removeAttr('data-src');
						};
					}
					$slider.css({'display': 'block'});
					$status
						.text(t)
						.delay(1000)
						.animate({top:-$status.innerHeight()});
				}
			});
		};
	}
})(jQuery);