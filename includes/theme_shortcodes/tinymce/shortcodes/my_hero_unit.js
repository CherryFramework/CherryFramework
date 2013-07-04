frameworkShortcodeAtts={
    attributes:[
            {
                label:"Title",
                id:"title",
                help:"Enter title."
            },
            {
                label:"Text",
                id:"text",
				controlType:"textarea-control", 
                help:"Enter text."
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
				label:"Button Style",
				id:"btn_style",
				controlType:"select-control",
				selectValues:['default', 'primary', 'info', 'success', 'warning', 'danger', 'inverse', 'link'],
				defaultValue: 'default', 
				defaultText: 'default',
				help:"Choose button style."
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
    shortcode:"hero_unit"
};