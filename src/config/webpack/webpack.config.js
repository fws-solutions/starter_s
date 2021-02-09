const path = require('path');
const regEx = new RegExp('node_modules\\' + path.sep + '(?!bootstrap).*');

module.exports = {
	mode: 'none',
	devtool: 'source-map',
	entry: {
		site: './src/js/site.js',
		admin: './src/js/admin.js'
	},
	output: {
		path: path.join(__dirname, './dist/'),
		filename: '[name].min.js'
	},
	module: {
		rules: [
			{
				test: /\.m?js$/,
				exclude: regEx,
				use: {
					loader: 'babel-loader',
					options: {
						presets: ['@babel/preset-env']
					}
				}
			}
		]
	},
	externals: {
		'jquery': 'jQuery'
	}
};
