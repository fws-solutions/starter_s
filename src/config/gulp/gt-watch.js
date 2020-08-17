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
	// gulp.watch('src/vue/**', gulp.series('vue-js'));

	// watch .php files
	gulp.watch(['template-views/**/*.php'], gulp.series(['html-lint-be']));
	done();
}

// export tasks
module.exports = {
	watchFiles: watchFiles
};
