const gulp = require('gulp');
const plumber = require('gulp-plumber');
const eslint = require('gulp-eslint');
const webpack = require('webpack-stream');
const webpackConfig = require('../webpack/webpack.config.js');
const gulpVars = require('./gulp-variables');

/*----------------------------------------------------------------------------------------------
	JS
 ----------------------------------------------------------------------------------------------*/
// task: build javascript files
gulp.task('js', js);

function js(src, dist, config) {
	webpackConfig.mainConfig.mode = gulpVars.productionBuild ? 'production' : 'development';

	return gulp.src(src)
		.pipe(plumber())
		.pipe(webpack(webpackConfig[config]))
		.pipe(gulp.dest(dist));
}

function jsBlock() {
	webpackConfig.mainConfig.mode = gulpVars.productionBuild ? 'production' : 'development';

	return gulp.src('template-views/blocks/**/*.js')
		.pipe(plumber())
		.pipe(webpack(webpackConfig['blocksConfig']))
		.pipe(gulp.dest('.'));
}

// task: validate javascript source files
gulp.task('js-lint', lintJS);

function lintJS() {
	return gulp.src(
		[
			gulpVars.jsSiteSRC,
			gulpVars.jsAdminSRC,
			gulpVars.jsBlocksSCR,
			'!template-views/blocks/**/*.min.js'
		])
		.pipe(eslint())
		.pipe(eslint.format())
		.pipe(eslint.failAfterError());
}

// export tasks
module.exports = {
	js: js,
	lintJS: lintJS
};
