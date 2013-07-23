frameworkShortcodeAtts={
	attributes:[
			{
				label:"Type of posts",
				id:"type",
				help:"This is the type of posts. Use post slug, e.g. \"portfolio\" or blank for posts from Blog"
			},			
			{
				label:"Columns",
				id:"columns",
				help:"Number of posts per row"
			},			
			{
				label:"Rows",
				id:"rows",
				help:"Number of rows"
			},
			{
				label:"Order by",
				id:"order_by",
				controlType:"select-control", 
				selectValues:['date', 'title', 'popular', 'random'],
				help:"Choose the order by mode."
			},
			{
				label:"Order",
				id:"order",
				controlType:"select-control", 
				selectValues:['DESC', 'ASC'],
				help:"Choose the order mode ( from Z to A or from A to Z)."
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
				label:"Meta",
				id:"meta",
				controlType:"select-control", 
				selectValues:['yes', 'no'],
				defaultValue: 'yes', 
				defaultText: 'yes',
				help:"Show a post meta?"
			},
			{
				label:"The number of characters in the excerpt",
				id:"excerpt_count",
				help:"How many characters are displayed in the excerpt?"
			},
			{
				label:"Link",
				id:"link",
				controlType:"select-control", 
				selectValues:['yes', 'no'],
				help:"Show link after posts, yes or no."
			},
			{
				label:"Link Text",
				id:"link_text",
				help:"Text for the link."
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
				label:"Custom class",
				id:"custom_class",
				help:"Use this field if you want to use a custom class for posts."
			}
	],
	defaultContent:"",
	shortcode:"posts_grid",
	shortcodeType: "text-replace"
};