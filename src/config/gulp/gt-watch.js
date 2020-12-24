const gulp = require('gulp');
const config = require('./gulp-config');

/*----------------------------------------------------------------------------------------------
	Watch
 ----------------------------------------------------------------------------------------------*/
gulp.task('watch-files', watchFiles);

function watchFiles(done) {
	// watch .scss files
	gulp.watch(config.scssSiteSRC, gulp.parallel(['css', 'sass-lint']));
	gulp.watch(config.scssAdminSRC, gulp.parallel(['css-admin', 'sass-lint']));

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
