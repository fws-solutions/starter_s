const path = require('path');

module.exports = {
	entry: {
		'site': './assets/js/site.js'
	},
	output: {
		path: path.join(__dirname, './dist/js/'),
		filename: '[name].js'
	},
	module: {
		loaders: [
			{
				test: path.join(__dirname),
				loader: 'babel-loader'
			}
		]
	}
};
