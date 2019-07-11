const gulp = require('gulp');
const plumber = require('gulp-plumber');
const postcss = require('gulp-postcss');
const concatCss = require('gulp-concat-css');
const cleanCss = require('gulp-clean-css');
const sass = require('gulp-sass');
const sassLint = require('gulp-sass-lint');
const flexBugsFix = require('postcss-flexbugs-fixes');
const sourcemaps = require('gulp-sourcemaps');
const autoprefixer = require('autoprefixer');
const rename = require('gulp-rename');
const globalVars = require('./_global-vars');

/*----------------------------------------------------------------------------------------------
	SCSS
 ----------------------------------------------------------------------------------------------*/
const sassSRC = ['assets/sass/**/*.scss'];

// compile scss files
gulp.task('plugins-css', pluginsCss);
gulp.task('css', css);
gulp.task('sass-lint', sasslint);

function css() {
	const processors = [
		autoprefixer({overrideBrowserslist: ['last 2 versions', 'ios >= 8']}),
		flexBugsFix
	];

	return gulp.src(sassSRC)
		.pipe(plumber(globalVars.msgERROR))
		.pipe(sourcemaps.init())
		.pipe(sass({outputStyle: globalVars.productionBuild ? 'compressed' : 'expanded'}))
		.pipe(postcss(processors))
		.pipe(rename('style.css'))
		.pipe(sourcemaps.write('.'))
		.pipe(gulp.dest('./'));
}

function pluginsCss() {
	return gulp.src(['assets/css-plugins/*.css'])
		.pipe(concatCss('plugins.min.css'))
		.pipe(cleanCss())
		.pipe(gulp.dest('dist'));
}

function sasslint() {
	return gulp.src(sassSRC)
		.pipe(sassLint({
			config: '.sass-lint.yml'
		}))
		.pipe(sassLint.format());
}

// export tasks
module.exports = {
	css: css,
	pluginsCss: pluginsCss,
	sasslint: sasslint
};
