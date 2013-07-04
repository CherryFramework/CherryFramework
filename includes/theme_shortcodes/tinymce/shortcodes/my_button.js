frameworkShortcodeAtts={
	attributes:[
			{
				label:"Button Text",
				id:"text",
				help:"Enter the text for button."
			},
			{
				label:"Button Link",
				id:"link",
				help:"Enter the link for button. (e.g. http://demolink.org)"
			},
			{
				label:"Style",
				id:"style",
				controlType:"select-control",
				selectValues:['default', 'primary', 'info', 'success', 'warning', 'danger', 'inverse', 'link'],
				defaultValue: 'default', 
				defaultText: 'default',
				help:"Choose button style."
			},
			{
				label:"Size",
				id:"size",
				controlType:"select-control",
				selectValues:['mini', 'small', 'normal', 'large'],
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
				label:"Display",
				id:"display",
				controlType:"select-control",
				selectValues:['inline', 'block'],
				defaultValue: 'normal', 
				defaultText: 'normal',
				help:"Choose between inline and block display options."
			},
			{
				label:"Class",
				id:"class",
				help:"Any CSS classes you want to add."
			},
            {
                label:"Icon",
                id:"icon",
				controlType:"select-control",
				selectValues:['no', 'pencil', 'refresh', 'comment', 'cogs', 'info-sign', 'user', 'book', 'twitter', 'arrow-right', 'circle-arrow-right'],
				defaultValue: 'no', 
				defaultText: 'no',
                help:"Optional image."
            }
	],
	defaultContent:"",
	shortcode:"button"
};