const gulp = require('gulp');
const plumber = require('gulp-plumber');
const sourcemaps = require('gulp-sourcemaps');
const clean = require('gulp-clean');
const concat = require('gulp-concat');
const uglify = require('gulp-uglify-es').default;
const eslint = require('gulp-eslint');
const webpack = require('webpack-stream');
const webpackConfig = require('../../../webpack.config.js');
const webpackVue = require('../../../webpack.vue.js');
const gulpif = require('gulp-if');
const globalVars = require('./_global-vars');
const destDir = 'dist';

/*----------------------------------------------------------------------------------------------
	JS
 ----------------------------------------------------------------------------------------------*/
gulp.task('js', gulp.series(gulp.parallel(siteJS, pluginsJS, vueJS), mergeJS, cleanJS));

// task: build javascript
gulp.task('site-js', siteJS);

function siteJS() {
	return gulp.src('src/js/**.js')
		.pipe(plumber())
		.pipe(webpack(webpackConfig))
		.pipe(gulpif(globalVars.productionBuild, uglify()))
		.pipe(gulp.dest(destDir));
}

// task: build vue
gulp.task('vue-js', vueJS);

webpackVue.mode = globalVars.productionBuild ? 'production' : 'development';

function vueJS() {
	return gulp.src('src/vue/app.js')
		.pipe(plumber())
		.pipe(webpack(webpackVue))
		.pipe(gulp.dest(destDir));
}

// task: validate javascript source files
gulp.task('lint-js', lintJS);

function lintJS() {
	return gulp.src('src/js/**/*.js')
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
		destDir + '/site.js',
		destDir + '/build.js'
	])
		.pipe(plumber(globalVars.msgERROR))
		.pipe(sourcemaps.init())
		.pipe(concat('site.min.js'))
		.pipe(gulpif(globalVars.productionBuild, uglify()))
		.pipe(sourcemaps.write('.'))
		.pipe(gulp.dest(destDir));
}

gulp.task('clean-js', cleanJS);

function cleanJS() {
	return gulp.src([
		destDir + '/plugins.js',
		destDir + '/site.js',
		destDir + '/build.js'], {read: false})
		.pipe(clean());
}

// export tasks
module.exports = {
	siteJS: siteJS,
	pluginsJS: pluginsJS,
	vueJS: vueJS,
	mergeJS: mergeJS,
	cleanJS: cleanJS
};
