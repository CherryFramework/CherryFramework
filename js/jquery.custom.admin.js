var hide_options;
jQuery(document).ready(function(){function l(c){"Audio"===c?k(m):"Video"===c?k(n):k(p)}function k(c){p.css("display","none");n.css("display","none");m.css("display","none");c.css("display","block")}function h(c){d.css("display","none");a.css("display","none");e.css("display","none");f.css("display","none");g.css("display","none");c.css("display","block")}var j=jQuery("#tz_portfolio_type"),p=jQuery("#tz-meta-box-portfolio-image"),n=jQuery("#tz-meta-box-portfolio-video"),m=jQuery("#tz-meta-box-portfolio-audio");currentType=
j.val();l(currentType);j.change(function(){currentType=jQuery(this).val();l(currentType)});var a=jQuery("#tz-meta-box-quote"),j=jQuery("#post-format-quote");a.css("display","none");var g=jQuery("#tz-meta-box-image"),q=jQuery("#post-format-image");g.css("display","none");var e=jQuery("#tz-meta-box-link"),r=jQuery("#post-format-link");e.css("display","none");var f=jQuery("#tz-meta-box-audio"),s=jQuery("#post-format-audio");f.css("display","none");var d=jQuery("#tz-meta-box-video"),t=jQuery("#post-format-video");
d.css("display","none");jQuery("#post-formats-select input").change(function(){"quote"==jQuery(this).val()?(a.css("display","block"),h(a)):"link"==jQuery(this).val()?(e.css("display","block"),h(e)):"audio"==jQuery(this).val()?(f.css("display","block"),h(f)):"video"==jQuery(this).val()?(d.css("display","block"),h(d)):"image"==jQuery(this).val()?(g.css("display","block"),h(g)):(a.css("display","none"),d.css("display","none"),e.css("display","none"),f.css("display","none"),g.css("display","none"))});
j.is(":checked")&&a.css("display","block");r.is(":checked")&&e.css("display","block");s.is(":checked")&&f.css("display","block");t.is(":checked")&&d.css("display","block");q.is(":checked")&&g.css("display","block");var b=jQuery("#page_template").val();"page-Portfolio2Cols-filterable.php"==b||"page-Portfolio3Cols-filterable.php"==b||"page-Portfolio4Cols-filterable.php"==b?jQuery("#tz-meta-box-category").show():jQuery("#tz-meta-box-category").hide();jQuery("#page_template").on("change",function(){b=
jQuery(this).val();"page-Portfolio2Cols-filterable.php"==b||"page-Portfolio3Cols-filterable.php"==b||"page-Portfolio4Cols-filterable.php"==b?jQuery("#tz-meta-box-category").show():jQuery("#tz-meta-box-category").hide()});jQuery("#framework-icon_type").on("change",function(){hide_options(jQuery(this).val(),jQuery(this))});hide_options=function(c,b){var d=c.toLocaleLowerCase().replace(" ","_"),a=b.parents("#options-table");"images"==d?(jQuery(".tupe_font_icon",a).parents("tr").css({display:"none"}),
jQuery(".tupe_images",a).parents("tr").css({display:"table-row"})):(jQuery(".tupe_images",a).parents("tr").css({display:"none"}),jQuery(".tupe_font_icon",a).parents("tr").css({display:"table-row"}))}});
