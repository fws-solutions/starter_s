const path = require('path');
const glob = require('glob');
const regEx = new RegExp('node_modules\\' + path.sep + '(?!bootstrap).*');

// Shared rules
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

// Entry configuration for site.js and admin.js
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
	externals: {
		'jquery': 'jQuery'
	}
};

// Dynamically generate entry for each .js file in template-views/blocks
const templateBlocksEntries = glob.sync('./template-views/blocks/**/*.js').reduce((acc, filePath) => {
	const entryKey = filePath.replace('./', '').replace('.js', '');
	acc[entryKey] = filePath;
	return acc;
}, {});

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
	}
};

// Export an array of configurations
module.exports = {
	mainConfig: mainConfig,
	blocksConfig: blocksConfig,
};
