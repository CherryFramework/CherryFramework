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
			success: function(x,t){
				reload_css(v);
			}
		});
	};
	function reload_css(v){
		var css = $('link#cherry-style-switcher-enqueue_skin-css');
		css[0].href = v;
	};
})(jQuery);