const gulp = require('gulp');
const config = require('./src/config/gulp/gulp-config');

/*----------------------------------------------------------------------------------------------
	Main Gulp Tasks
 ----------------------------------------------------------------------------------------------*/
const gulpTasks = {
	gtMjml: require('./src/config/gulp/gt-mjml'),
	gtHtmlLint: require('./src/config/gulp/gt-htmllint'),
	gtCss: require('./src/config/gulp/gt-css'),
	gtJs: require('./src/config/gulp/gt-js'),
	gtWatch: require('./src/config/gulp/gt-watch'),
	gtClean: require('./src/config/gulp/gt-clean')
}

// build all files for production
gulp.task('build', done => config.buildFiles('prod', gulpTasks, done));

// build all files for development
gulp.task('build-dev', done => config.buildFiles('dev', gulpTasks, done));

// build all files for development and start watch mode
gulp.task('watch', done => config.buildFiles('watch', gulpTasks, done));

// remove dist folder
gulp.task('reset-dev', config.deleteDistFolder);
