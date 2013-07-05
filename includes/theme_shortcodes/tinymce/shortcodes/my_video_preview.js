frameworkShortcodeAtts={
	attributes:[
			{
				label:"Link to post.",
				id:"post_url",
				help:"You can insert the link into the post that contains video from YouTube or Vimeo. Example link: http://demolink.org/blog/video-post-type/"
			},
			{
				label:"Need to display the name of the post?",
				id:"title",
				controlType:"select-control",
				selectValues:['yes', 'no'],
				defaultValue: 'yes', 
				defaultText: 'yes',
				help:"You can display or hide the name of the post with the following shortcode."
			},
			{
				label:"Need to display the post creation date?",
				id:"date",
				controlType:"select-control",
				selectValues:['yes', 'no'],
				defaultValue: 'yes', 
				defaultText: 'yes',
				help:"You can show or hide the post creation date with the following shortcode."
			},
			{
				label:"Need to display the author of the post?",
				id:"author",
				controlType:"select-control",
				selectValues:['yes', 'no'],
				defaultValue: 'yes', 
				defaultText: 'yes',
				help:"You can show or hide the author of the post with the following shortcode."
			},
			{
				label:"Custom class",
				id:"custom_class",
				help:"Use this field if you want to use a custom class."
			}
	],
	defaultContent:"",
	shortcode:"video_preview"
};