const gulp = require('gulp');
const gulpif = require('gulp-if');
const plumber = require('gulp-plumber');
const postcss = require('gulp-postcss');
const sass = require('gulp-sass');
const sassLint = require('gulp-sass-lint');
const postCssFlexBugsFix = require('postcss-flexbugs-fixes');
const postCssInlineSvg = require('postcss-inline-svg');
const sourcemaps = require('gulp-sourcemaps');
const autoprefixer = require('autoprefixer');
const rename = require('gulp-rename');
const gulpVars = require('./gulp-variables');

/*----------------------------------------------------------------------------------------------
	SCSS
 ----------------------------------------------------------------------------------------------*/
const processors = [
	autoprefixer({overrideBrowserslist: ['last 2 versions', 'ios >= 8']}),
	postCssFlexBugsFix,
	postCssInlineSvg
];

// compile scss files
gulp.task('css', css.bind(null, gulpVars.scssSiteSRC, 'site'));
gulp.task('css-admin', css.bind(null, gulpVars.scssAdminSRC, 'admin'));
gulp.task('sass-lint', sasslint);

function css(src, type) {
	return gulp.src(src)
		.pipe(plumber(gulpVars.msgERROR))
		.pipe(sourcemaps.init())
		.pipe(sass({outputStyle: gulpVars.productionBuild ? 'compressed' : 'expanded'}))
		.pipe(postcss(processors))
		.pipe(gulpif(
			type === 'site',
			rename('style.css'),
			rename('admin.css')
		))
		.pipe(sourcemaps.write('.'))
		.pipe(gulpif(
			type === 'site',
			gulp.dest('./'),
			gulp.dest('dist')
		));
}

function sasslint() {
	return gulp.src(gulpVars.scssAllSRC)
		.pipe(sassLint({
			config: '.sass-lint.yml'
		}))
		.pipe(sassLint.format());
}

// export tasks
module.exports = {
	css: css,
	sasslint: sasslint
};
