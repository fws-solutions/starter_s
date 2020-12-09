const gulp = require('gulp');
const mjml = require('gulp-mjml');
const mjmlEngine = require('mjml');
const rename = require('gulp-rename');

/*----------------------------------------------------------------------------------------------
	MJML
 ----------------------------------------------------------------------------------------------*/
function compileMjml() {
	return gulp.src('./src/emails/cf7/**/*.mjml')
		.pipe(mjml(mjmlEngine, {beautify: true}))
		.pipe(rename(function(path) {
			path.dirname = 'cf7';
		}))
		.pipe(gulp.dest('dist'));
}

gulp.task('mjml', compileMjml);

function copyHtmlFiles() {
	return gulp.src('./src/emails/cf7/**/*.html')
		.pipe(rename(function(path) {
			path.dirname = 'cf7';
		}))
		.pipe(gulp.dest('dist'));
}

gulp.task('cf7', gulp.parallel(
	copyHtmlFiles,
	compileMjml
));

// export tasks
module.exports = {
	copyHtmlFiles: copyHtmlFiles,
	compileMjml: compileMjml
};
