const gulp = require('gulp');

/*----------------------------------------------------------------------------------------------
	Watch
 ----------------------------------------------------------------------------------------------*/
gulp.task('watch-files', watchFiles);

function watchFiles(done) {
	// watch .scss files
	gulp.watch(['assets/sass/**/*.scss'], gulp.parallel(['css', 'sass-lint']));

	// watch .js files
	gulp.watch('assets/js/**/*.js', gulp.series('js'));

	done();
}

// export tasks
module.exports = {
	watchFiles: watchFiles
};
