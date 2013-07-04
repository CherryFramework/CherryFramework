frameworkShortcodeAtts={
	attributes:[
			{
				label:"How many testimonials to show?",
				id:"num",
				help:"This is how many recent testimonials will be displayed."
			},
			{
				label:"Do you want to show the featured image?",
				id:"thumb",
				controlType:"select-control", 
				selectValues:['true', 'false'],
				defaultValue: 'true', 
				defaultText: 'true',
				help:"Enable or disable featured image."
			},
			{
				label:"The number of characters in the excerpt",
				id:"excerpt_count",
				help:"How many characters are displayed in the excerpt?"
			},
			{
				label:"Custom class",
				id:"custom_class",
				help:"Use this field if you want to use a custom class."
			}
	],
	defaultContent:"",
	shortcode:"recenttesti"
};