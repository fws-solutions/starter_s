const path = require('path');

module.exports = {
	mode: 'none',
	entry: './src/config/admin/js/admin.js',
	output: {
		path: path.join(__dirname, './dist/'),
		filename: 'admin.js'
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
	}
};
