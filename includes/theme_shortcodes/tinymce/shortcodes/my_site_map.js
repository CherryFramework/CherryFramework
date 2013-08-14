frameworkShortcodeAtts={
	attributes:[
			{
				label:"Sitemap title.",
				id:"title",
				help:"Enter your title used on sitemap page."
			},
			{
				label:"Sitemap type",
				id:"type",
				controlType:"select-control",
				selectValues:['Lines', 'Columns'],
				defaultValue: 'Lines', 
				defaultText: 'Lines',
				help:"Select sitemap type which will be displayed."
			},
			{
				label:"Custom class",
				id:"custom_class",
				help:"Use this field if you want to use a custom class."
			}
	],
	defaultContent:"",
	shortcode:"site_map"
};