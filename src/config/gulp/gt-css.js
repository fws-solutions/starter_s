const gulp = require('gulp');
const gulpif = require('gulp-if');
const plumber = require('gulp-plumber');
const postcss = require('gulp-postcss');
const sass = require('gulp-sass')(require('sass'));
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
gulp.task('css-blocks', css.bind(null, gulpVars.scssBlocksSRC, 'blocks'));
gulp.task('css-admin', css.bind(null, gulpVars.scssAdminSRC, 'admin'));
gulp.task('sass-lint', sasslint);

function css(src, type) {
	return gulp.src(src)
		.pipe(plumber(gulpVars.msgERROR))
		.pipe(sourcemaps.init())
		.pipe(sass({outputStyle: gulpVars.productionBuild ? 'compressed' : 'expanded'}))
		.pipe(postcss(processors))
		.pipe(gulpif(
			type === !'blocks',
			rename(cssFileName(type))
		))
		.pipe(sourcemaps.write('.'))
		.pipe(gulp.dest(cssDestDir(type)));
}

function cssDestDir(type) {
	let dest = '';

	switch(type) {
		case 'site':
			dest = './';
			break;
		case 'blocks':
			dest = './template-views/blocks';
			break;
		case 'admin':
			dest = 'dist';
			break;
	}

	return dest;
}

function cssFileName(type) {
	let name = '';

	switch(type) {
		case 'site':
			name = 'style.css';
			break;
		case 'admin':
			name = 'admin.css';
			break;
	}

	return name;
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
