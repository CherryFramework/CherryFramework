(function($) {
	// Main Layout Option (FullWidth or Fixed)
	wp.customize(CURRENT_THEME + '[main_layout]', function(value) {
		value.bind(function(to) {
			get_slider_template_part(curslider, 'main_layout', to);
		})
	});
	// Color Skin Option (ex. Dark or Light)
	wp.customize(CURRENT_THEME + '[color_skin]', function(value) {
		value.bind(function(to) {
			update_option('cherry_color_skin', to);
		})
	});
	// Color Schemes Option
	wp.customize(CURRENT_THEME + '[links_color]', function(value) {
		value.bind(function(to) {
			update_option('cherry_color_schemes', to);
		})
	});
	// Slider Type Option (Camera, Accardion or None)
	wp.customize(CURRENT_THEME + '[slider_type]', function(value) {
		value.bind(function(to) {
			curslider = to;
			get_slider_template_part(to, 'slider_type');
		})
	});
	function update_option(n,v){
		var $data = {
				action: 'custom_update_option',
				option_name: n,
				option_value: v
			};

		$.ajax({
			type: 'POST',
			url: ajaxurl,
			data: $data,
			dataType: 'html',
			beforeSend: function(){
				fade_out();
			},
			success: function(){
				if (n === 'cherry_color_skin') {
					change_css(v);
				};
				if (n === 'cherry_color_schemes') {
					reload_css();
				};
				fade_in();
			}
		});
	};
	function change_css(url){
		var css = $('link#cherry-style-switcher-skin-css');
		css[0].href = url;
	};
	function reload_css(){
		var query = '?reload=' + new Date().getTime(),
			css = $('link#cherry-style-switcher-schemes-css'),
			i = 0;

		if ( css[i].href.indexOf('main-style.css') > -1) {
			css[i].href = css[i].href.replace('main', 'demo');
		} else {
			css[i].href = css[i].href.replace(/\?.*|$/, query);
		}
	};
	function get_slider_template_part(slider,option,value){
		var $data = {
				action: 'require_template_part',
				template_part: slider
			};

		if ('none_slider' === slider) {

			$('#slider-wrapper')
				.parent()
				.html('<div class="slider_off"></div>');
		} else {

			if ($('#slider-wrapper').length) {
				$slider_wrap = $('#slider-wrapper');
			} else {
				$slider_wrap = $('.slider_off');
			}

			$.ajax({
				type: 'POST',
				url: ajaxurl,
				data: $data,
				dataType: 'html',
				beforeSend: function(){
					fade_out();
				},
				success: function(x){
					if ('main_layout' === option) {
						change_layout(value);
					};
					$slider_wrap
						.hide()
						.parent()
						.html('<div id="slider-wrapper">' + x + '</div>');

					var imgs = $('.accordion_wrap img');
					if (imgs){
						for (var i = 0; i < imgs.length; i++) {
							img = $('.accordion_wrap img').eq(i);
							img.attr({'src':img.attr('data-src')}).removeAttr('data-src');
						};
					}
					$slider_wrap.show();
					fade_in();
				}
			});
		}
	};
	function change_layout(to) {
		var $body = $('body');
		if ('fullwidth' === to) {
			$body.removeClass('cherry-fixed-layout');
		} else if ('fixed' === to) {
			$body.addClass('cherry-fixed-layout');
		}
	}
	function fade_out(){
		$('#style-switcher-spin').show();
		spinner_init();
		$('#style-switcher-spin')
			.css('zIndex', 999)
			.css('opacity', 0)
			.stop()
			.animate({opacity:0.75},
					500,
					'easeInQuad');
	}
	function fade_in(){
		$('#style-switcher-spin')
			.stop()
			.animate(
				{opacity:0},
				1000,
				'easeOutQuad',
				function() {
					$(this)
						.css('zIndex', 0)
						.hide()
				}
			)
			.html('');
	}
	function spinner_init(){
		var opts = {
			lines: 11,
			length: 10,
			width: 5,
			radius: 14,
			direction: -1,
			corners: 1,
			color: '#fff',
			speed: 1.0,
			trail: 5,
			shadow: true
		},
		spinner = new Spinner(opts).spin($('#style-switcher-spin')[0]);
	}
})(jQuery);