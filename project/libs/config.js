require.config({
	baseUrl:'libs',
	paths:{
		'jquery':[
		'https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min',
		'jquery-3.3.1.min'
		],
		'knockoutjs':[
		'https://cdnjs.cloudflare.com/ajax/libs/knockout/3.4.2/knockout-min',
		'knockout-3.4.2'
		],
		'bootstrap': 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min',
		'product':'../modules/product'
	},
	shim:{
		'jquery':{
			exports:'$'
		},
		'bootstrap':{
			deps: ['jquery']
		}
	}
});
