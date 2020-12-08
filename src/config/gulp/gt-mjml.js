const gulp = require('gulp')
const mjml = require('gulp-mjml')

/*----------------------------------------------------------------------------------------------
	MJML
 ----------------------------------------------------------------------------------------------*/
function compileMjml() {
	return gulp.src('./test.mjml')
		.pipe(mjml())
		.pipe(gulp.dest('dist'))
}

gulp.task('mjml', compileMjml);

// export tasks
module.exports = {
	compileMjml: compileMjml
};
