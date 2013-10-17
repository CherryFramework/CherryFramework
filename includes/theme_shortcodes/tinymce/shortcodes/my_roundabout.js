frameworkShortcodeAtts={
	attributes:[
		{
			label:"Title",
			id:"title",
			help:"Title for your roundabout carousel."
		},
		{
			label:"How many posts to show?",
			id:"num",
			help:"This is how many items will be displayed."
		},
		{
			label:"Type of posts",
			id:"type",
			controlType:"select-control", 
			selectValues:['blog', 'portfolio', 'testimonial', 'our team'],
			defaultValue: 'post', 
			defaultText: 'blog',
			help:"Choose the type of posts."
		},
		{
			label:"Image width",
			id:"thumb_width",
			help:"Set width for item's image."
		},
		{
			label:"Image height",
			id:"thumb_height",
			help:"Set height for item's image."
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
			label:"More button text",
			id:"more_button_text",
			help:"Enter the button text"
		},
		{
			label:"More button link",
			id:"more_button_link",
			help:"Enter the button link"
		},
		{
			label:"Custom class",
			id:"custom_class",
			help:"Use this field if you want to use a custom class."
		}
	],
	defaultContent:"",
	shortcode:"roundabout"
};