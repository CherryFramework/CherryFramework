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
				selectValues:['small', 'smaller', 'smallest'],
				defaultValue: 'small', 
				defaultText: 'small',
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
				label:"Align",
				id:"align",
				controlType:"select-control", 
				selectValues:['left', 'right', 'center'],
				defaultValue: 'left', 
				defaultText: 'left',
				help:"Alignment of grid - left, right, or center."
			},
			{
				label:"Custom class",
				id:"custom_class",
				help:"Use this field if you want to use a custom class."
			}
	],
	defaultContent:"",
	shortcode:"mini_posts_grid",
	shortcodeType: "text-replace"
};