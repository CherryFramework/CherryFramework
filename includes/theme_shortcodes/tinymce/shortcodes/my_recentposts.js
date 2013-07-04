frameworkShortcodeAtts={
	attributes:[
			{
				label:"How many posts to show?",
				id:"num",
				help:"This is how many recent posts will be displayed."
			},
			{
				label:"Type of posts",
				id:"type",
				help:"This is the type of posts. Use post slug, e.g. \"portfolio\""
			},
			{
				label:"Post Format",
				id:"post_format",
				controlType:"select-control", 
				selectValues:['standard', 'aside', 'quote', 'link', 'image', 'audio', 'video'],
				defaultValue: 'standard', 
				defaultText: 'standard',
				help:"Choose displaying post format."
			},
			{
				label:"Meta",
				id:"meta",
				controlType:"select-control", 
				selectValues:['true', 'false'],
				defaultValue: 'false', 
				defaultText: 'false',
				help:"Enable or disable meta information."
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
				label:"Image width",
				id:"thumb_width",
				help:"Set width for your featured images."
			},
			{
				label:"Image height",
				id:"thumb_height",
				help:"Set height for your featured images."
			},
			{
				label:"Link Text for post",
				id:"more_text_single",
				help:"Link Text for post."
			},
			{
				label:"Which category to pull from? (for Blog posts)",
				id:"category",
				help:"Enter the slug of the category you'd like to pull posts from. Leave blank if you'd like to pull from all categories."
			},
			{
				label:"Which category to pull from? (for Custom posts)",
				id:"custom_category",
				help:"Enter the slug of the category you'd like to pull posts from. Leave blank if you'd like to pull from all categories."
			},
			{
				label:"The number of characters in the excerpt",
				id:"excerpt_count",
				help:"How many characters are displayed in the excerpt?"
			},
			{
				label:"Custom class",
				id:"custom_class",
				help:"Use this field if you want to use a custom class for posts."
			}
	],
	defaultContent:"",
	shortcode:"recent_posts",
	shortcodeType: "text-replace"
};