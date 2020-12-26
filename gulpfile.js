/*----------------------------------------------------------------------------------------------
	Main Gulp Tasks
 ----------------------------------------------------------------------------------------------*/
const gulp = require('gulp');
const gulpFunct = require('./src/config/gulp/gulp-functions');
const gulpVars = require('./src/config/gulp/gulp-variables');
const gulpTasks = {
	gtMjml: require('./src/config/gulp/gt-mjml'),
	gtHtmlLint: require('./src/config/gulp/gt-htmllint'),
	gtCss: require('./src/config/gulp/gt-css'),
	gtJs: require('./src/config/gulp/gt-js'),
	gtWatch: require('./src/config/gulp/gt-watch')
}

// build all files for production
gulp.task('build', done => gulpFunct.buildFiles('prod', gulpTasks, done));

// build all files for development
gulp.task('build-dev', done => gulpFunct.buildFiles('dev', gulpTasks, done));

// build all files for development and start watch mode
gulp.task('watch', done => gulpFunct.buildFiles('watch', gulpTasks, done));

// remove dist folder
gulp.task('reset-dev', gulpFunct.deleteDistFolder);
