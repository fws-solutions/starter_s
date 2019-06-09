const gulp = require('gulp');
const clean = require('gulp-clean');
const globalVars = require('./assets/config/gulp-tasks/_global-vars');

// import gulp parts
require('./assets/config/gulp-tasks/gt-iconfonts');
const gtCss = require('./assets/config/gulp-tasks/gt-css');
const gtJs = require('./assets/config/gulp-tasks/gt-js');
const gtWatch = require('./assets/config/gulp-tasks/gt-watch');

// prepare for build
function prodBuild(done) {
	globalVars.productionBuild = true;
	done();
}

function devBuild(done) {
	globalVars.productionBuild = false;
	done();
}

// build all files for production
gulp.task('build', gulp.series(
	globalVars.createDistFolder,
	prodBuild,
	gulp.parallel(
		gtCss.pluginsCss,
		gtCss.css,
		gulp.series(gtJs.siteJS, gtJs.pluginsJS, gtJs.mergeJS, gtJs.cleanJS)
	)
));

// build all files for development
gulp.task('build-dev', gulp.series(
	globalVars.createDistFolder,
	devBuild,
	gulp.parallel(
		gtCss.pluginsCss,
		gtCss.css,
		gulp.series(gtJs.siteJS, gtJs.pluginsJS, gtJs.mergeJS, gtJs.cleanJS)
	)
));

// delete dist folder
gulp.task('reset-dev', function () {
	return gulp.src('dist', {read: false})
		.pipe(clean());
});

// start dev tasks
gulp.task('watch', gulp.series(
	globalVars.createDistFolder,
	devBuild,
	gulp.parallel(
		gtCss.pluginsCss,
		gtCss.css,
		gulp.series(gtJs.siteJS, gtJs.pluginsJS, gtJs.mergeJS, gtJs.cleanJS)
	),
	gtWatch.watchFiles
));