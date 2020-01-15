const {join} = require('path');
const {VueLoaderPlugin} = require('vue-loader');

module.exports = {
	// mode set in gulpfile
	entry: join(__dirname, 'src/vue/app.js'),
	output: {
		path: join(__dirname, 'build'),
		filename: 'build.js'
	},
	module: {
		rules: [
			{
				test: /\.js$/,
				loader: 'babel-loader',
				options: {
					presets: ['@babel/preset-env']
				}
			},
			{
				test: /\.vue$/,
				loader: 'vue-loader'
			},
			{
				test: /\.scss$/,
				use: [
					'vue-style-loader',
					'css-loader',
					{
						loader: 'sass-loader',
						options: {
							prependData: `
								@import "./src/scss/config/_variables.scss";
								@import "./src/scss/helpers/_mixins.scss";
							`
						}
					}
				]
			}
		]
	},
	plugins: [
		new VueLoaderPlugin()
	]
};
