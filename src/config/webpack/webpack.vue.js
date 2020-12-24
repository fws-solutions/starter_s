const path = require('path');

module.exports = {
	outputDir: path.resolve(__dirname, '../../../dist/vue-build'),
	filenameHashing: false,
	chainWebpack: config => {
		config.plugins.delete('html');
		config.plugins.delete('preload');
		config.plugins.delete('prefetch');

		config.module
			.rule('scss')
			.test(/\.scss$/)
			.use('vue-style-loader')
			.loader('vue-style-loader')
			.end();

		const svgRule = config.module.rule('svg');

		svgRule.uses.clear();

		svgRule
			.use('babel-loader')
			.loader('babel-loader')
			.end()
			.use('vue-svg-loader')
			.loader('vue-svg-loader');
	},
	css: {
		extract: false,
		loaderOptions: {
			sass: {
				additionalData: `
                    @import "./src/scss/config/_variables.scss";
                    @import "./src/scss/helpers/_mixins.scss";
                `
			}
		}
	}
};
