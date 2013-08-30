jQuery(function(a) {
	function c() {
		window.location.replace("export.php")
	}
	function d() {
		window.location.replace("options-permalink.php")
	}
	a("form#widget-export-settings").submit(function() {
		window.setTimeout(c, 4E3)
	});
	a("form#import-widget-data").submit(function(b) {
		b.preventDefault();
		a.post(ajaxurl, a("#import-widget-data").serialize(), function(a, textStatus) {
			if(textStatus == 'success'){
				d();
			}
		})
	});
});