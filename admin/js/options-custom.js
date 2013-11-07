jQuery(document).ready(function(a) {
    function g() {
        "checked" == a("#slider_type_camera_slider").attr("checked") ? (a(".slider_type_1").css({display: "block"}), a(".slider_type_2").css({display: "none"})) : "checked" == a("#slider_type_accordion_slider").attr("checked") ? (a(".slider_type_1").css({display: "none"}), a(".slider_type_2").css({display: "block"})) : "checked" == a("#slider_type_none_slider").attr("checked") && (a(".slider_type_1").css({display: "none"}), a(".slider_type_2").css({display: "none"}))
    }
    function j() {
        var a = jQuery(this), b = a.parent(), d = a.attr("href"), e = a.hasClass("backup_theme") ? "backup" : "restore";
        jQuery.ajax({url: system_folder + e + ".php?theme_folder=" + d,type: "POST",beforeSend: function() {
                b.css({background: 'url("images/wpspin_light.gif") center no-repeat'});
                a.css({visibility: "hidden"})
            },success: function(f) {
                if ("backup" == e) {
                    f = f.split(",");
                    var c = jQuery('input[value="' + d + '"]');
                    jQuery("#date_" + d).html(f[0]);
                    jQuery("#version_" + d).html(f[1]);
                    c.removeClass("no_backup");
                    k(c)
                } else if ("restore" == e) {
                    var c = parseFloat(f), g = parseFloat(jQuery("#update_version").text());
                    jQuery("#your_version_" + d).html(f);
                    g > c && jQuery("#update_framework").fadeTo(0, 1).next(".cap").remove()
                }
                b.css({background: 'url("images/yes.png") center no-repeat'});
                setTimeout(function() {
                    b.css({background: "none"});
                    a.css({visibility: "visible"})
                }, 3E3)
            }});
        return !1
    }
    function l(a) {
        var b = a.parents(".theme_box");
        jQuery(".backup_theme, .restore_theme, .download_backup", b).attr({href: a.val()});
        k(a)
    }
    function k(a) {
        var b = a.parents(".theme_box"), d = a.val(), b = jQuery('.restore_theme[href="' + d + '"], .download_backup[href="' + d + '"]', b);
        a.hasClass("no_backup") ? (b.fadeTo(0, 0.5), jQuery(".cap", b.parent())[0] || b.parent().append('<span class="cap"></span>')) : b.fadeTo(0, 1).next(".cap").remove()
    }
    a(".fade").delay(1E3).fadeOut(1E3);
    a(".colorSelector").each(function() {
        var c = this, b = a(c).next("input").attr("value");
        a(this).ColorPicker({color: b,onShow: function(b) {
                a(b).fadeIn(0);
                return !1
            },onHide: function(b) {
                a(b).fadeOut(0);
                return !1
            },onChange: function(b, e) {
                a(c).children("div").css("backgroundColor", "#" + e);
                a(c).next("input").attr("value", "#" + e)
            }})
    });
    var c = a(".nav-tab-wrapper");
    a("a", c).click(function() {
        window.location.hash = a(this).attr("href");
        return !1
    });
    a(window).bind("hashchange", function() {
        var h = window.location.hash;
        a(".group").css({display: "none"});
        a("a", c).removeClass("nav-tab-active");
        "" != h ? (a(h).css({display: "block"}), a("a[href=" + h + "]", c).addClass("nav-tab-active")) : (a(".group:first").css({display: "block"}), a("a:first", c).addClass("nav-tab-active"))
    }).trigger("hashchange");
    a(".of-radio-img-img").click(function() {
        a(this).parent().parent().find(".of-radio-img-img").removeClass("of-radio-img-selected");
        a(this).addClass("of-radio-img-selected")
    });
    a(".of-radio-img-label").hide(0);
    a(".of-radio-img-img").show(0);
    a(".of-radio-img-radio").hide(0);
    a('#section-folio_excerpt input[type="radio"]').click(function() {
        "yes" == a(this).filter(":checked").val() ? a("#section-folio_excerpt_count").fadeIn(0) : a("#section-folio_excerpt_count").fadeOut(0)
    });
    "no" == a('#section-folio_excerpt input[type="radio"]:checked').val() && a("#section-folio_excerpt_count").hide();
    a('#section-logo_type input[type="radio"]').click(function() {
        "text_logo" == a(this).filter(":checked").val() ? (a("#section-logo_typography").show(0), a("#section-logo_url").hide(0)) : (a("#section-logo_typography").hide(0), a("#section-logo_url").show(0))
    });
    "image_logo" == a('#section-logo_type input[type="radio"]:checked').val() && (a("#section-logo_typography").hide(0), a("#section-logo_url").show(0));
    a('#section-footer_menu input[type="radio"]').click(function() {
        "true" == a(this).filter(":checked").val() ? a("#section-footer_menu_typography").fadeIn(0) : a("#section-footer_menu_typography").fadeOut(0)
    });
    "false" == a('#section-footer_menu input[type="radio"]:checked').val() && a("#section-footer_menu_typography").hide(0);
    a('#section-main_layout input[type="radio"]').click(function() {
        "fixed" == a(this).filter(":checked").val() ? a("#section-main_background").fadeIn(0) : a("#section-main_background").fadeOut(0)
    });
    "fullwidth" == a('#section-main_layout input[type="radio"]:checked').val() && a("#section-main_background").hide(0);
    a("#section-slider_type .of-radio-img-img").click(g);
    g();
    jQuery(".theme_name:checked").each(function() {
        l(jQuery(this))
    });
    jQuery(".disable_button").fadeTo(0, 0.5);
    jQuery(".download_backup").bind("click", function() {
        var a = jQuery(this);
        window.location.href = system_folder + "download_backup.php?theme_folder=" + a.attr("href");
        return !1
    });
    jQuery(".backup_theme").bind("click", j);
    jQuery(".restore_theme").bind("click", j);
    jQuery(".theme_name").bind("change", function() {
        l(jQuery(this))
    })
});
