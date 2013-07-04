frameworkShortcodeAtts={
    attributes:[
			{
                label:"Banner Image",
                id:"img",
                help:"Enter path to the banner image. (e.g. http://demolink.org/src/img/pic.png)"
            },
			{
				label:"Banner Link",
				id:"banner_link",
				help:"Enter the link for banner. (e.g. http://demolink.org)"
			},
            {
                label:"Banner Title",
                id:"title",
                help:"Enter the banner title."
            },
            {
                label:"Banner Text",
                id:"text",
				controlType:"textarea-control", 
                help:"Enter the text for banner."
            },
			{
				label:"Button Text",
				id:"btn_text",
				help:"Enter the text for button."
			},
			{
				label:"Target",
				id:"target",
				controlType:"select-control",
				selectValues:['_blank', '_self', '_parent', '_top'],
				defaultValue: '_self', 
				defaultText: '_self',
				help:"The target attribute specifies a window or a frame where the linked document is loaded."
			},
			{
				label:"Custom class",
				id:"custom_class",
				help:"Use this field if you want to use a custom class."
			}
    ],
    defaultContent:"",
    shortcode:"banner"
};