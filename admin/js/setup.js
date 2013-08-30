jQuery(function(a){

	// IMPORT

	/* data */
	a("#upload-file").change(function() {
		a("#upload-file").val() ? a('#upload-widget-data input[type="submit"]').removeAttr("disabled") : a('#upload-widget-data input[type="submit"]').attr("disabled", "disabled")
	});
	a('#import-upload-form input[type="submit"]').attr("disabled", "disabled");
	a('#import-upload-form input[type="submit"]').click(function() {
		a(".import").find(".progress").addClass("active").find(".bar").animate({width: "25%"}, {duration: 1E3})
	});
	a("#upload").change(function() {
		a("#upload").val() ? a('#import-upload-form input[type="submit"]').removeAttr("disabled") : a('#import-upload-form input[type="submit"]').attr("disabled", "disabled")
	});
	a("#user-wrap select").change(function() {
		currSelectText = a("#user-wrap select :selected").text();
		selectText = a("#user-wrap option:first-child").text();
		selectText != currSelectText ? a('#dataForm input[type="submit"]').removeAttr("disabled") : a('#dataForm input[type="submit"]').attr("disabled", "disabled")
	});
	a('#dataForm input[type="submit"]').click(function() {
		a("#user-wrap select").attr("disabled", "disabled"),
		a(".import").find(".progress").addClass("active").find(".bar").stop().animate({width: "50%"}, {duration: 15E4})
	});

	/* widgets */
	a('#upload-widget-data input[type="submit"]').click(function() {
		a(".import").find(".progress").addClass("active").find(".bar").stop().animate({width: "75%"}, {duration: 2E3})
	});
	a("#import-widgets").click(function() {
		a(".import").find(".progress").addClass("active").find(".bar").stop().animate({width: "100%"}, {duration: 2E3})
	});
	var b, n1, n2;
	b = window.location.href;
	n1 = b.lastIndexOf('step');
	n2 = b.lastIndexOf('&');
	if (n2 > n1) {
		b = b.substr(n1, (n2-n1));
	} else {
		b = b.substr(n1);
	}
	switch (b) {
		case 'step=2':
			a(".progress").find(".bar").css({width: "27%"}),
			a(".progress .start").removeClass("in-progress").addClass("success"),
			a(".progress .step1").addClass("in-progress")
			break;
		case 'step=3':
			a(".progress").find(".bar").css({width: "50%"}),
			a(".progress .start").removeClass("in-progress").addClass("success"),
			a(".progress .step1").removeClass("in-progress").addClass("success"),
			a(".progress .step2").addClass("in-progress")
			break;
		case 'step=4':
			a(".progress").find(".bar").css({width: "75%"}),
			a(".progress .start").removeClass("in-progress").addClass("success"),
			a(".progress .step1").removeClass("in-progress").addClass("success"),
			a(".progress .step2").removeClass("in-progress").addClass("success"),
			a(".progress .step3").addClass("in-progress")
			break;
		default:
			break;
	}
});