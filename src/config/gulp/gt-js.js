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
//gulp.task('jsblock', jsBlock);

function js() {
	webpackConfig.mainConfig.mode = gulpVars.productionBuild ? 'production' : 'development';

	return gulp.src([gulpVars.jsSiteSRC, gulpVars.jsAdminSRC])
		.pipe(plumber())
		.pipe(webpack(webpackConfig.mainConfig))
		.pipe(gulp.dest(gulpVars.distSRC));
}

// function jsBlock() {
// 	webpackConfig.mainConfig.mode = gulpVars.productionBuild ? 'production' : 'development';
//
// 	return gulp.src('template-views/blocks/slider/slider.js')
// 		.pipe(plumber())
// 		.pipe(webpack(webpackConfig.blocksConfig))
// 		.pipe(gulp.dest('.'));
// }

// task: validate javascript source files
gulp.task('js-lint', lintJS);

function lintJS() {
	return gulp.src('src/js/**/*.js')
		.pipe(eslint())
		.pipe(eslint.format())
		.pipe(eslint.failAfterError());
}

// export tasks
module.exports = {
	js: js,
	lintJS: lintJS
};
