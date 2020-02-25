const gulp = require('gulp');
const plumber = require('gulp-plumber');
const sourcemaps = require('gulp-sourcemaps');
const clean = require('gulp-clean');
const concat = require('gulp-concat');
const uglify = require('gulp-uglify-es').default;
const eslint = require('gulp-eslint');
const webpack = require('webpack-stream');
const webpackConfig = require('../webpack/webpack.config.js');
const webpackVue = require('../webpack/webpack.config.vue.js');
const webpackAdmin = require('../webpack/webpack.config.admin.js');
const gulpif = require('gulp-if');
const globalVars = require('./_global-vars');
const destDir = 'dist';

/*----------------------------------------------------------------------------------------------
	JS
 ----------------------------------------------------------------------------------------------*/
gulp.task('js', gulp.series(gulp.parallel(siteJS, pluginsJS, vueJS, adminJS), mergeJS, cleanJS));

// task: build admin javascript
gulp.task('admin-js', adminJS);

function adminJS() {
	return gulp.src('src/config/admin/js/**.js')
		.pipe(plumber())
		.pipe(sourcemaps.init())
		.pipe(webpack(webpackAdmin))
		.pipe(gulpif(globalVars.productionBuild, uglify()))
		.pipe(sourcemaps.write('.'))
		.pipe(gulp.dest(destDir));
}

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
	adminJS: adminJS,
	siteJS: siteJS,
	pluginsJS: pluginsJS,
	vueJS: vueJS,
	mergeJS: mergeJS,
	cleanJS: cleanJS,
	lintJS: lintJS
};
