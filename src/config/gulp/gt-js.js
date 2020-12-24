const fs = require('fs');
const gulp = require('gulp');
const plumber = require('gulp-plumber');
const sourcemaps = require('gulp-sourcemaps');
const concat = require('gulp-concat');
const uglify = require('gulp-uglify-es').default;
const eslint = require('gulp-eslint');
const webpack = require('webpack-stream');
const webpackConfig = require('../webpack/webpack.config.js');
const webpackAdmin = require('../webpack/webpack.config.admin.js');
const gulpif = require('gulp-if');
const config = require('./gulp-config');
const destDir = 'dist';

/*----------------------------------------------------------------------------------------------
	JS
 ----------------------------------------------------------------------------------------------*/
gulp.task('js', gulp.series(gulp.parallel(siteJS, pluginsJS), mergeJS));

// task: build admin javascript
gulp.task('admin-js', adminJS);

function adminJS() {
	return gulp.src('src/config/admin/js/**.js')
		.pipe(plumber())
		.pipe(sourcemaps.init())
		.pipe(webpack(webpackAdmin))
		.pipe(gulpif(config.productionBuild, uglify()))
		.pipe(sourcemaps.write('.'))
		.pipe(gulp.dest(destDir));
}

// task: build javascript
gulp.task('site-js', siteJS);

function siteJS() {
	return gulp.src('src/js/**.js')
		.pipe(plumber())
		.pipe(webpack(webpackConfig))
		.pipe(gulpif(config.productionBuild, uglify()))
		.pipe(gulp.dest(destDir));
}

// task: validate javascript source files
gulp.task('js-lint', lintJS);

function lintJS() {
	return gulp.src('src/js/_site/*.js')
		.pipe(eslint())
		.pipe(eslint.format())
		.pipe(eslint.failAfterError());
}

// task: concats all the plugins (Slick, etc)
gulp.task('plugins-js', pluginsJS);

function pluginsJS() {
	return gulp.src(['src/js/_plugins/**/*.js'])
		.pipe(concat('plugins.js'))
		.pipe(gulp.dest(destDir));
}

// task: combines all the JS files from destination folder
gulp.task('merge-js', mergeJS);

function mergeJS() {
	return gulp.src([
		destDir + '/plugins.js',
		destDir + '/site.js'
	])
		.pipe(plumber(config.msgERROR))
		.pipe(sourcemaps.init())
		.pipe(concat('site.min.js'))
		.pipe(gulpif(config.productionBuild, uglify()))
		.pipe(sourcemaps.write('.'))
		.pipe(gulp.dest(destDir));
}

// export tasks
module.exports = {
	adminJS: adminJS,
	siteJS: siteJS,
	pluginsJS: pluginsJS,
	mergeJS: mergeJS,
	lintJS: lintJS
};
