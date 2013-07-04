frameworkShortcodeAtts={
    attributes:[
            {
                label:"Title",
                id:"title",
                help:"Enter text for box title."
            },
            {
                label:"Subtitle",
                id:"subtitle",
                help:"Enter text for box subtitle."
            },
            {
                label:"Icon",
                id:"icon",
				controlType:"select-control",
				selectValues:['no', 'icon1', 'icon2', 'icon3', 'icon4', 'icon5', 'icon6', 'icon7', 'icon8', 'icon9', 'icon10'],
				defaultValue: 'icon1', 
				defaultText: 'icon1',
                help:"Select the icon image for box."
            },
            {
                label:"Text",
                id:"text",
				controlType:"textarea-control", 
                help:"Enter text for box."
            },
			{
				label:"Button Text",
				id:"btn_text",
				help:"Enter the text for button."
			},
			{
				label:"Button Link",
				id:"btn_link",
				help:"Enter the link for button. (e.g. http://demolink.org)"
			},
			{
				label:"Button Size",
				id:"btn_size",
				controlType:"select-control",
				selectValues:['small', 'normal', 'large'],
				defaultValue: 'normal', 
				defaultText: 'normal',
				help:"Choose button size."
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
    shortcode:"service_box"
};