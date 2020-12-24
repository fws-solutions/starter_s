const gulp = require('gulp');
const plumber = require('gulp-plumber');
const uglify = require('gulp-uglify-es').default;
const eslint = require('gulp-eslint');
const webpack = require('webpack-stream');
const webpackConfig = require('../webpack/webpack.config.js');
const gulpif = require('gulp-if');
const config = require('./gulp-config');

/*----------------------------------------------------------------------------------------------
	JS
 ----------------------------------------------------------------------------------------------*/
// task: build javascript files
gulp.task('js', js);

function js() {
	return gulp.src([config.jsSiteSRC, config.jsAdminSRC])
		.pipe(plumber())
		.pipe(webpack(webpackConfig))
		.pipe(gulpif(config.productionBuild, uglify()))
		.pipe(gulp.dest(config.distSRC));
}

// task: validate javascript source files
gulp.task('js-lint', lintJS);

function lintJS() {
	return gulp.src('src/js/_site/*.js')
		.pipe(eslint())
		.pipe(eslint.format())
		.pipe(eslint.failAfterError());
}

// export tasks
module.exports = {
	js: js,
	lintJS: lintJS
};
