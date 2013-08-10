jQuery(function(a) {
	function c() {
		window.location.replace("export.php")
	}
	function d() {
		window.location.replace("admin.php?page=options-framework-import&step=2")
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
	var e = a("<div/>").css({}), b = a("#upload-file").wrap(e);
	b.change(function() {
		$this = 
		a(this);
		sub = $this.val().lastIndexOf("\\") + 1;
		new_string = $this.val().substring(sub);
		a("#output-text").text(new_string);
		a("#output-text").fadeIn("slow")
	});
	a("#upload-button").click(function() {
		b.click()
	}).show()
});