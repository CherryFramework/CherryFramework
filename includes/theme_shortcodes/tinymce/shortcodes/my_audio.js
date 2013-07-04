frameworkShortcodeAtts={
	attributes:[
			{
				label:"File type",
				id:"type",
				controlType:"select-control",
				selectValues:['mp3', 'wav', 'ogg'],
				defaultValue: 'mp3', 
				defaultText: 'mp3',
				help:"Please, choose audio format."
			},
			{
				label:"File URL",
				id:"file",
				help:"Enter the full url to the audio file like this:<br/>http://demolink.org/uploads/file.mp3"
			},
			{
				label:"Title",
				id:"title",
				help:"Title for your file."
			}			
	],
	defaultContent:"",
	shortcode:"audio"
};