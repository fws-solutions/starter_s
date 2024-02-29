const path = require('path');
const glob = require('glob');
const regEx = new RegExp('node_modules\\' + path.sep + '(?!bootstrap).*');

/** @description Shared rules and vars. */
const sharedRules = [
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

const externals = {
	'jquery': 'jQuery'
}

/** @description Configuration for site.js and admin.js */
const mainConfig = {
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
		rules: sharedRules
	},
	externals: externals
};

/** @description Dynamically generate entry for each .js file in template-views/blocks */
const allEntries = glob.sync('./template-views/blocks/**/*.js', { ignore: '**/*.min.js' });
const templateBlocksEntries = allEntries.reduce((acc, filePath) => {
	const entryKey = filePath.replace('./', '').replace('.js', '');

	if (filePath.search('.min.js') < 0) {
		acc[entryKey] = filePath;
	}

	return acc;
}, {});

/** @description Configuration for template-views/blocks .js files */
const blocksConfig = {
	mode: 'none',
	devtool: 'source-map',
	entry: templateBlocksEntries,
	output: {
		path: path.resolve(__dirname),
		filename: '[name].min.js'
	},
	module: {
		rules: sharedRules
	},
	externals: externals
};

/** @description Export an array of configurations */
module.exports = {
	mainConfig: mainConfig,
	blocksConfig: blocksConfig,
};
