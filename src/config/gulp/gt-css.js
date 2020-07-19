const gulp = require('gulp');
const plumber = require('gulp-plumber');
const postcss = require('gulp-postcss');
const concatCss = require('gulp-concat-css');
const cleanCss = require('gulp-clean-css');
const sass = require('gulp-sass');
const sassLint = require('gulp-sass-lint');
const postCssFlexBugsFix = require('postcss-flexbugs-fixes');
const postCssInlineSvg = require('postcss-inline-svg');
const sourcemaps = require('gulp-sourcemaps');
const autoprefixer = require('autoprefixer');
const rename = require('gulp-rename');
const globalVars = require('./_global-vars');

/*----------------------------------------------------------------------------------------------
	SCSS
 ----------------------------------------------------------------------------------------------*/
const sassSRC = ['src/scss/**/*.scss', 'template-views/**/**/*.scss'];
const adminSassSRC = ['src/config/admin/scss/admin.scss'];

const processors = [
	autoprefixer({overrideBrowserslist: ['last 2 versions', 'ios >= 8']}),
	postCssFlexBugsFix,
	postCssInlineSvg
];

// compile scss files
gulp.task('plugins-css', pluginsCss);
gulp.task('css', css.bind(null, sassSRC, 'style.css', './'));
gulp.task('css-admin', css.bind(null, adminSassSRC, 'admin.css', 'dist'));
gulp.task('sass-lint', sasslint);

function css(src, name, dest) {
	return gulp.src(src)
		.pipe(plumber(globalVars.msgERROR))
		.pipe(sourcemaps.init())
		.pipe(sass({outputStyle: globalVars.productionBuild ? 'compressed' : 'expanded'}))
		.pipe(postcss(processors))
		.pipe(rename(name))
		.pipe(sourcemaps.write('.'))
		.pipe(gulp.dest(dest));
}

function pluginsCss() {
	return gulp.src(['src/css/*.css'])
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
