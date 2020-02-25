const gulp = require('gulp');

/*----------------------------------------------------------------------------------------------
	Watch
 ----------------------------------------------------------------------------------------------*/
gulp.task('watch-files', watchFiles);

function watchFiles(done) {
	// watch .scss files
	gulp.watch(['src/scss/**/*.scss', 'template-views/**/**/*.scss'], gulp.parallel(['css', 'sass-lint']));
	gulp.watch(['src/config/admin/scss/**/*.scss'], gulp.parallel(['css-admin']));

	// watch .js files
	gulp.watch('src/js/**/*.js', gulp.series('js-lint', 'js'));

	// watch vue folder
	gulp.watch('src/vue/**', gulp.series('js'));

	// watch .scss files
	gulp.watch(['__fe-template-parts/**/*.php'], gulp.parallel(['html-lint-fe', 'html-w3']));
	gulp.watch(['template-parts/**/*.php'], gulp.parallel(['html-lint-be', 'html-w3']));

	done();
}

// export tasks
module.exports = {
	watchFiles: watchFiles
};
