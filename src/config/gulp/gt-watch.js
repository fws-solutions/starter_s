const gulp = require('gulp');
const gulpVars = require('./gulp-variables');

/*----------------------------------------------------------------------------------------------
	Watch
 ----------------------------------------------------------------------------------------------*/
gulp.task('watch-files', watchFiles);

function watchFiles(done) {
	// watch .scss files
	gulp.watch(gulpVars.scssSiteSRC, gulp.parallel(['css', 'sass-lint']));
	gulp.watch(gulpVars.scssAdminSRC, gulp.parallel(['css-admin', 'sass-lint']));

	// watch .js files
	gulp.watch('src/js/**/*.js', gulp.series('js-lint', 'js'));

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
