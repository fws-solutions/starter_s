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
	gulp.watch(['src/config/admin/js/**/*.js'], gulp.parallel(['admin-js']));

	// watch vue folder
	// gulp.watch('src/vue/**', gulp.series('vue-js'));

	// watch cf7 folder
	gulp.watch(['./src/emails/cf7/**/*.html', './src/emails/cf7/**/*.mjml'], gulp.series('cf7'));

	// watch .php files
	gulp.watch(['template-views/**/*.php'], gulp.series(['html-lint-be']));
	done();
}

// export tasks
module.exports = {
	watchFiles: watchFiles
};
