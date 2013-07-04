frameworkShortcodeAtts={
	attributes:[
			{
				label:"Type of posts",
				id:"type",
				help:"This is the type of posts. Use post slug, e.g. \"portfolio\" or blank for posts from Blog"
			},			
			{
				label:"How many posts to show?",
				id:"numb",
				help:"Total number of posts, -1 for all posts"
			},
			{
				label:"Thumbnails",
				id:"thumbs",
				controlType:"select-control", 
				selectValues:['normal', 'large'],
				defaultValue: 'large', 
				defaultText: 'large',
				help:"Size of post thumbnails"
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
				label:"Post Content",
				id:"post_content",
				controlType:"select-control", 
				selectValues:['excerpt', 'content', 'none'],
				defaultValue: 'excerpt', 
				defaultText: 'excerpt',
				help:"Show excerpts or full content"
			},
			{
				label:"Order by",
				id:"order_by",
				controlType:"select-control", 
				selectValues:['date', 'title', 'popular', 'random'],
				defaultValue: 'date', 
				defaultText: 'date',
				help:"Choose the order by mode."
			},
			{
				label:"Order",
				id:"order",
				controlType:"select-control", 
				selectValues:['DESC', 'ASC'],
				defaultValue: 'DESC', 
				defaultText: 'DESC',
				help:"Choose the order mode (from Z to A or from A to Z)."
			},
			{
				label:"Link",
				id:"link",
				controlType:"select-control", 
				selectValues:['yes', 'no'],
				defaultValue: 'yes', 
				defaultText: 'yes',
				help:"Show link after posts, yes or no?"
			},
			{
				label:"Link Text",
				id:"link_text",
				help:"Text for the link."
			},
			{
				label:"Tags",
				id:"tags",
				controlType:"select-control", 
				selectValues:['yes', 'no'],
				defaultValue: 'yes', 
				defaultText: 'yes',
				help:"Show post tags, yes or no?"
			},
			{
				label:"Custom class",
				id:"custom_class",
				help:"Use this field if you want to use a custom class."
			}
	],
	defaultContent:"",
	shortcode:"posts_list",
	shortcodeType: "text-replace"
};