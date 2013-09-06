<?php 
header("Content-Type:text/javascript");

//Setup URL to WordPress
$absolute_path = __FILE__;
$path_to_wp = explode( 'wp-content', $absolute_path );
$wp_url = $path_to_wp[0];

//Access WordPress
require_once( $wp_url.'/wp-load.php' );

//URL to TinyMCE plugin folder
$plugin_url = get_template_directory_uri().'/includes/theme_shortcodes/tinymce/';
?>
(function(){
	
	var icon_url = '<?php echo $plugin_url; ?>' + 'images/icon_shortcodes.png';

	tinymce.create(
		"tinymce.plugins.MyThemeShortcodes",
		{
			init: function(d,e) {
					
					d.addCommand( "myThemeOpenDialog",function(a,c){
						
						// Grab the selected text from the content editor.
						selectedText = '';
					
						if ( d.selection.getContent().length > 0 ) {
					
							selectedText = d.selection.getContent();
							
						} // End IF Statement
						
						myThemeSelectedShortcodeType = c.identifier;
						myThemeSelectedShortcodeTitle = c.title;
						
						jQuery.get(e+"/dialog.php",function(b){
							
							jQuery('#shortcode-options').addClass( 'shortcode-' + myThemeSelectedShortcodeType );
							
							// Skip the popup on certain shortcodes.
							
							switch ( myThemeSelectedShortcodeType ) {
								
				// warning
								
								case 'warning':
								
								var a = '[warning]'+selectedText+'[/warning]';
								
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								
								break;
				
				// error
								
								case 'error':
								
								var a = '[error]'+selectedText+'[/error]';
								
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								
								break;
								
				// succes
								
								case 'succes':
								
								var a = '[succes]'+selectedText+'[/succes]';
								
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								
								break;
								
				// info
								
								case 'info':
								
								var a = '[info]'+selectedText+'[/info]';
								
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								
								break;
								
				// tags
								
								case 'tags':
								
								var a = '[tags]';
								
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								
								break;
								
				// dropcap
								
								case 'dropcap':
								
								var a = '[dropcap]'+selectedText+'[/dropcap]';
								
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								
								break;
								
				// highlight
								
								case 'highlight':
								
								var a = '[highlight]'+selectedText+'[/highlight]';
								
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								
								break;
								
				// frame
								
								case 'frame':
								
								var a = '[frame align="none"]'+selectedText+'[/frame]';
								
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								
								break;								
								
				// Horizontal Ruel
								
								case 'hr':
								
								var a = '[hr]';
								
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								
								break;
								
				// List Unstyled
								
								case 'list_un':
								
								var a = '[list_un]'+selectedText+'[/list_un]';
								
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								
								break;
				
				// Check list
								
								case 'check_list':
								
								var a = '[check_list]'+selectedText+'[/check_list]';
								
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								
								break;
								
				// Check2 list
								
								case 'check2_list':
								
								var a = '[check2_list]'+selectedText+'[/check2_list]';
								
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								
								break;
								
				// Arrow list
								
								case 'arrow_list':
								
								var a = '[arrow_list]'+selectedText+'[/arrow_list]';
								
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								
								break;
								
				// Arrow2 list
								
								case 'arrow2_list':
								
								var a = '[arrow2_list]'+selectedText+'[/arrow2_list]';
								
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								
								break;
								
				// Star list
								
								case 'star_list':
								
								var a = '[star_list]'+selectedText+'[/star_list]';
								
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								
								break;
								
				// Plus list
								
								case 'plus_list':
								
								var a = '[plus_list]'+selectedText+'[/plus_list]';
								
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								
								break;
								
				// Minus list
								
								case 'minus_list':
								
								var a = '[minus_list]'+selectedText+'[/minus_list]';
								
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								
								break;
								
				// Custom list
								
								case 'custom_list':
								
								var a = '[custom_list]'+selectedText+'[/custom_list]';
								
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								
								break;
								
				// Vertical Rule
								
								case 'vr':
								
								var a = '[vr]'+selectedText+'[/vr]';
								
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								
								break;
								
				// Small Horizontal Rule
								
								case 'sm_hr':
								
								var a = '[sm_hr]';
								
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								
								break;
				
				// spacer
								
								case 'spacer':
								
								var a = '[spacer]';
								
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								
								break;
								
				// Clear
								
								case 'clear':
								
								var a = '[clear]';
								
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								
								break;

				// Extra Wrap
								
								case 'extra_wrap':
								
								var a = '[extra_wrap]'+selectedText+'[/extra_wrap]';
								
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								
								break;
								
				// row
								
								case 'row':
								
								var a = '[row]'+selectedText+'[/row]';
								
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								
								break;
								
								
				// row inner
								
								case 'row_in':
								
								var a = '[row_in]'+selectedText+'[/row_in]';
								
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								
								break;
				
								
				// span1
								
								case 'span1':
								
								var a = '[span1]'+selectedText+'[/span1]';
								
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								
								break;
								
				// span2
								
								case 'span2':
								
								var a = '[span2]'+selectedText+'[/span2]';
								
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								
								break;
				
				// span3
								
								case 'span3':
								
								var a = '[span3]'+selectedText+'[/span3]';
								
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								
								break;
				
				// span4
								
								case 'span4':
								
								var a = '[span4]'+selectedText+'[/span4]';
								
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								
								break;
				
				// span5
								
								case 'span5':
								
								var a = '[span5]'+selectedText+'[/span5]';
								
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								
								break;
				
				// span6
								
								case 'span6':
								
								var a = '[span6]'+selectedText+'[/span6]';
								
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								
								break;
				
				// span7
								
								case 'span7':
								
								var a = '[span7]'+selectedText+'[/span7]';
								
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								
								break;
				
				// span8
								
								case 'span8':
								
								var a = '[span8]'+selectedText+'[/span8]';
								
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								
								break;
				
				// span9
								
								case 'span9':
								
								var a = '[span9]'+selectedText+'[/span9]';
								
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								
								break;
				
				// span10
								
								case 'span10':
								
								var a = '[span10]'+selectedText+'[/span10]';
								
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								
								break;
				
				// span11
								
								case 'span11':
								
								var a = '[span11]'+selectedText+'[/span11]';
								
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								
								break;
				
				// span12
								
								case 'span12':
								
								var a = '[span12]'+selectedText+'[/span12]';
								
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								
								break;
								
				// row_fluid
								
								case 'row_fluid':
								
								var a = '[row_fluid]'+selectedText+'[/row_fluid]';
								
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								
								break;

				// one_half
								
								case 'one_half':
								
								var a = '[one_half]'+selectedText+'[/one_half]';
								
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								
								break;

				// one_third
								
								case 'one_third':
								
								var a = '[one_third]'+selectedText+'[/one_third]';
								
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								
								break;

				// two_third
								
								case 'two_third':
								
								var a = '[two_third]'+selectedText+'[/two_third]';
								
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								
								break;

				// one_fourth
								
								case 'one_fourth':
								
								var a = '[one_fourth]'+selectedText+'[/one_fourth]';
								
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								
								break;


				// three_fourth
								
								case 'three_fourth':
								
								var a = '[three_fourth]'+selectedText+'[/three_fourth]';
								
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								
								break;


				// one_sixth
								
								case 'one_sixth':
								
								var a = '[one_sixth]'+selectedText+'[/one_sixth]';
								
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								
								break;


				// five_sixth
								
								case 'five_sixth':
								
								var a = '[five_sixth]'+selectedText+'[/five_sixth]';
								
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								
								break;
				
								
				// dspan - 50x50
								
								case 'dspan_50x50':
								
								var a = '[span6]'+selectedText+'[/span6][span6][/span6]';
								
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								
								break;
								
				// dspan - 66x33
								
								case 'dspan_66x33':
								
								var a = '[span8]'+selectedText+'[/span8][span4][/span4]';
								
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								
								break;
								
				// dspan - 33x66
								
								case 'dspan_33x66':
								
								var a = '[span4]'+selectedText+'[/span4][span8][/span8]';
								
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								
								break;
								
				// tspan - 33x33x33
								
								case 'tspan_33x33x33':
								
								var a = '[span4]'+selectedText+'[/span4][span4][/span4][span4][/span4]';
								
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								
								break;
								
				// tspan - 50x25x25
								
								case 'tspan_50x25x25':
								
								var a = '[span6]'+selectedText+'[/span6][span3][/span3][span3][/span3]';
								
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								
								break;
								
				// tspan - 25x50x25
								
								case 'tspan_25x50x25':
								
								var a = '[span3]'+selectedText+'[/span3][span6][/span6][span3][/span3]';
								
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								
								break;
								
				// tspan - 25x25x50
								
								case 'tspan_25x25x50':
								
								var a = '[span3]'+selectedText+'[/span3][span3][/span3][span6][/span6]';
								
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								
								break;
								
				// qspan - 25x25x25x25
								
								case 'qspan_25x25x25x25':
								
								var a = '[span3]'+selectedText+'[/span3][span3][/span3][span3][/span3][span3][/span3]';
								
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								
								break;
								
				
				 // blockquote
								
								case 'blockquote':
								
								var a = '[blockquote]'+selectedText+'[/blockquote]';
								
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								
								break;
								

				// address
								
								case 'address':
								
								var a = '[address]'+selectedText+'[/address]';
								
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								
								break;
								
				// table
								
								case 'table':
								
								var a = '[table td1="#" td2="Title" td3="Value"] [td1] 1 [/td1] [td2] some title 1 [/td2] [td3] some value 1 [/td3] [/table]';
								
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
				
								break;
								
				
				// tabs
								
								case 'tabs':
								
								var a = '[tabs tab1="Title #1" tab2="Title #2" tab3="Title #3"] [tab1] Tab 1 content... [/tab1] [tab2] Tab 2 content... [/tab2] [tab3] Tab 3 content... [/tab3] [/tabs]';
								
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
				
								break;
				
				// Accordion
								
								case 'accordions':
								
								var a = '[accordions] [accordion title="title1" visible="yes"] tab content [/accordion] [accordion title="title2"] another content tab [/accordion] [/accordions]';
								
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								
								break;
								
					// Close icon
								
								case 'close':
								
								var a = '[close dismiss="alert"]';
								
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								
								break;
								
					// Well
								
								case 'well':
								
								var a = '[well size="well-normal"]'+selectedText+'[/well]';
								
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								
								break;

					// Template URL
								
								case 'template_url':
								
								var a = '[template_url]';
								
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								
								break;
								
					// small
								
								case 'small':
								
								var a = '[small]'+selectedText+'[/small]';
								
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								
								break;
															
								default:
								
								jQuery("#dialog").remove();
								jQuery("body").append(b);
								jQuery("#dialog").hide();
								var f=jQuery(window).width();
								b=jQuery(window).height();
								f=720<f?720:f;
								f-=80;
								b-=84;
							
							tb_show("Insert "+ myThemeSelectedShortcodeTitle +" Shortcode", "#TB_inline?width="+f+"&height="+b+"&inlineId=dialog");jQuery("#shortcode-options h3:first").text(""+c.title+" Shortcode Settings");
							
								break;
							
							} // End SWITCH Statement
						
						}
												 
					)
					 
					} 
				);

				},
					
				createControl:function(d,e){
				
						if(d=="shortcodes_button"){
						
							d=e.createMenuButton("shortcodes_button",{
								title:"<?php echo theme_locals("insert_shortcode"); ?>",
								image:icon_url,
								icons:false
								});
								
								var a=this;d.onRenderMenu.add(function(c,b){
								c=b.addMenu({title:"<?php echo theme_locals("posts"); ?>"});
										a.addWithDialog(c,"<?php echo theme_locals("posts_grid"); ?>","posts_grid");
										a.addWithDialog(c,"<?php echo theme_locals("posts_list"); ?>","posts_list");
										a.addWithDialog(c,"<?php echo theme_locals("mini_posts_grid"); ?>","mini_posts_grid");
										a.addWithDialog(c,"<?php echo theme_locals("mini_posts_list"); ?>","mini_posts_list");
										a.addWithDialog(c,"<?php echo theme_locals("recentposts"); ?>","recentposts");
										a.addWithDialog(c,"<?php echo theme_locals("recenttesti"); ?>","recenttesti");
								c=b.addMenu({title:"<?php echo theme_locals("basic"); ?>"});b.addSeparator();
										a.addWithDialog(c,"<?php echo theme_locals("banner"); ?>","banner");
										a.addWithDialog(c,"<?php echo theme_locals("recentcomments"); ?>","recentcomments");
										a.addWithDialog(c,"<?php echo theme_locals("post_cycle"); ?>","post_cycle");
										a.addWithDialog(c,"<?php echo theme_locals("carousel"); ?>","carousel");
										a.addWithDialog(c,"<?php echo theme_locals("roundabout"); ?>","roundabout");
										a.addWithDialog(c,"<?php echo theme_locals("service_box"); ?>","service_box");
										a.addWithDialog(c,"<?php echo theme_locals("hero_unit"); ?>","hero_unit");
										a.addWithDialog(c,"<?php echo theme_locals("categories"); ?>","categories");
										a.addWithDialog(c,"<?php echo theme_locals("tags"); ?>","tags");
										
								c=b.addMenu({title:"<?php echo theme_locals("columns"); ?>"});
										a.addWithDialog(c,"row","row");
										a.addWithDialog(c,"row inner","row_in");
										a.addWithDialog(c,"span1","span1");
										a.addWithDialog(c,"span2","span2");
										a.addWithDialog(c,"span3","span3");
										a.addWithDialog(c,"span4","span4");
										a.addWithDialog(c,"span5","span5");
										a.addWithDialog(c,"span6","span6");
										a.addWithDialog(c,"span7","span7");
										a.addWithDialog(c,"span8","span8");
										a.addWithDialog(c,"span9","span9");
										a.addWithDialog(c,"span10","span10");
										a.addWithDialog(c,"span11","span11");
										a.addWithDialog(c,"span12","span12");
								c=b.addMenu({title:"<?php echo theme_locals("fluid_column"); ?>"});
										a.addWithDialog(c,"row fluid","row_fluid");
										a.addWithDialog(c,"1/2","one_half");
										a.addWithDialog(c,"1/3","one_third");
										a.addWithDialog(c,"2/3","two_third");
										a.addWithDialog(c,"1/4","one_fourth");
										a.addWithDialog(c,"3/4","three_fourth");
										a.addWithDialog(c,"1/6","one_sixth");
										a.addWithDialog(c,"5/6","five_sixth");
								c=b.addMenu({title:"<?php echo theme_locals("2_columns"); ?>"});
										a.addWithDialog(c,"1/2 | 1/2","dspan_50x50");
										a.addWithDialog(c,"2/3 | 1/3","dspan_66x33");
										a.addWithDialog(c,"1/3 | 2/3","dspan_33x66");
								c=b.addMenu({title:"<?php echo theme_locals("3_columns"); ?>"});
										a.addWithDialog(c,"1/3 | 1/3 | 1/3","tspan_33x33x33");
										a.addWithDialog(c,"1/2 | 1/4 | 1/4","tspan_50x25x25");
										a.addWithDialog(c,"1/4 | 1/2 | 1/4","tspan_25x50x25");
										a.addWithDialog(c,"1/4 | 1/4 | 1/2","tspan_25x25x50");
								c=b.addMenu({title:"<?php echo theme_locals("4_columns"); ?>"});;b.addSeparator();
										a.addWithDialog(c,"1/4 | 1/4 | 1/4 | 1/4","qspan_25x25x25x25");
								c=b.addMenu({title:"<?php echo theme_locals("elements"); ?>"});
										a.addWithDialog(c,"<?php echo theme_locals("label"); ?>","label");
										a.addWithDialog(c,"<?php echo theme_locals("highlight"); ?>","highlight");
										a.addWithDialog(c,"<?php echo theme_locals("button"); ?>","button");
										a.addWithDialog(c,"<?php echo theme_locals("dropcap"); ?>","dropcap");
										a.addWithDialog(c,"<?php echo theme_locals("blockquote"); ?>","blockquote");
										a.addWithDialog(c,"<?php echo theme_locals("icon"); ?>","icon");
										a.addWithDialog(c,"<?php echo theme_locals("frame"); ?>","frame");
										a.addWithDialog(c,"<?php echo theme_locals("hr"); ?>","hr");
										a.addWithDialog(c,"<?php echo theme_locals("sm_hr"); ?>","sm_hr");
										a.addWithDialog(c,"<?php echo theme_locals("vr"); ?>","vr");
										a.addWithDialog(c,"<?php echo theme_locals("spacer"); ?>","spacer");
										a.addWithDialog(c,"<?php echo theme_locals("progressbar"); ?>","progressbar");
										a.addWithDialog(c,"<?php echo theme_locals("address"); ?>","address");
										a.addWithDialog(c,"<?php echo theme_locals("clear"); ?>","clear");
										a.addWithDialog(c,"<?php echo theme_locals("extra_wrap"); ?>","extra_wrap");
								c=b.addMenu({title:"<?php echo theme_locals("lists"); ?>"});
										a.addWithDialog(c,"<?php echo theme_locals("list_un"); ?>","list_un");
										a.addWithDialog(c,"<?php echo theme_locals("check_list"); ?>","check_list");
										a.addWithDialog(c,"<?php echo theme_locals("check2_list"); ?>","check2_list");
										a.addWithDialog(c,"<?php echo theme_locals("arrow_list"); ?>","arrow_list");
										a.addWithDialog(c,"<?php echo theme_locals("arrow2_list"); ?>","arrow2_list");
										a.addWithDialog(c,"<?php echo theme_locals("star_list"); ?>","star_list");
										a.addWithDialog(c,"<?php echo theme_locals("plus_list"); ?>","plus_list");
										a.addWithDialog(c,"<?php echo theme_locals("minus_list"); ?>","minus_list");
										a.addWithDialog(c,"<?php echo theme_locals("custom_list"); ?>","custom_list");
								c=b.addMenu({title:"<?php echo theme_locals("misc"); ?>"});
										a.addWithDialog(c,"<?php echo theme_locals("alert_box"); ?>","alert_box");
										a.addWithDialog(c,"<?php echo theme_locals("close"); ?>","close");
										a.addWithDialog(c,"<?php echo theme_locals("well"); ?>","well");
										a.addWithDialog(c,"<?php echo theme_locals("small"); ?>","small");
										a.addWithDialog(c,"<?php echo theme_locals("title"); ?>","title");
										a.addWithDialog(c,"<?php echo theme_locals("template_url"); ?>","template_url");
										a.addWithDialog(c,"<?php echo theme_locals("site_map"); ?>","site_map");
								a.addWithDialog(b,"<?php echo theme_locals("video_preview"); ?>","video_preview");
								a.addWithDialog(b,"<?php echo theme_locals("tabs"); ?>","tabs");
								a.addWithDialog(b,"<?php echo theme_locals("accordions"); ?>","accordions");
								a.addWithDialog(b,"<?php echo theme_locals("table"); ?>","table");
								a.addWithDialog(b,"<?php echo theme_locals("map"); ?>","map");

							});
							
							return d
						
						} // End IF Statement
						
						return null
					},
		
				addImmediate:function(d,e,a){d.add({title:e,onclick:function(){tinyMCE.activeEditor.execCommand("mceInsertContent",false,a)}})},
				
				addWithDialog:function(d,e,a){d.add({title:e,onclick:function(){tinyMCE.activeEditor.execCommand("myThemeOpenDialog",false,{title:e,identifier:a})}})},
		
				getInfo:function(){ return{longname:"Shortcode Generator",author:"VisualShortcodes.com",authorurl:"http://visualshortcodes.com",infourl:"http://visualshortcodes.com/shortcode-ninja",version:"1.0"} }
			}
		);
		
		tinymce.PluginManager.add("MyThemeShortcodes",tinymce.plugins.MyThemeShortcodes)
	}
)();
