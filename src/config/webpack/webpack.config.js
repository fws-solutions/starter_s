const path = require('path');

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
				exclude: /(node_modules|bower_components)/,
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
