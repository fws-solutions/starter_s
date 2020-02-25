const {join} = require('path');
const {VueLoaderPlugin} = require('vue-loader');

module.exports = {
	// mode set in gulpfile
	entry: './src/vue/app.js',
	output: {
		path: join(__dirname, './dist/'),
		filename: 'build.js'
	},
	module: {
		rules: [
			{
				enforce: 'pre',
				test: /\.(js|vue)$/,
				loader: 'eslint-loader',
				exclude: /node_modules/
			},
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
