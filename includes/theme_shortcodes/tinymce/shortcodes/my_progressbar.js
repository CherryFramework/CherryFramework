frameworkShortcodeAtts={
	attributes:[
			{
				label:"Value",
				id:"value",
				help:"Enter the value for bar (%)."
			},
			{
				label:"Type",
				id:"type",
				controlType:"select-control",
				selectValues:['info', 'success', 'warning', 'danger'],
				defaultValue: 'basic', 
				defaultText: 'basic',
				help:"Choode the type for bar."
			},
			{
				label:"Gradient Type",
				id:"grad_type",
				controlType:"select-control",
				selectValues:['vertical', 'striped'],
				defaultValue: 'vertical', 
				defaultText: 'vertical',
				help:"Choode the gradient type for bar."
			},
			{
				label:"Animated",
				id:"animated",
				controlType:"select-control",
				selectValues:['no', 'yes'],
				defaultValue: 'no', 
				defaultText: 'no',
				help:"Animated progressbar?"
			},
			{
				label:"Custom class",
				id:"custom_class",
				help:"Use this field if you want to use a custom class."
			}
	],
	defaultContent:"",
	shortcode:"progressbar"
};