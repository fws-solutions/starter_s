const gulp = require('gulp');
const clean = require('gulp-clean');
const tap = require('gulp-tap');
const fs = require('fs');
const path = require('path');
const colors = require('ansi-colors');
const globalVars = require('./src/config/gulp/_global-vars');

/*----------------------------------------------------------------------------------------------
	Prepare and Run all Gulp Tasks
 ----------------------------------------------------------------------------------------------*/
const sassSRC = ['src/scss/**/*.scss', 'template-views/**/**/*.scss'];
const adminSassSRC = ['src/config/admin/scss/admin.scss'];

const gtHtmlLint = require('./src/config/gulp/gt-htmllint');
const gtCss = require('./src/config/gulp/gt-css');
const gtJs = require('./src/config/gulp/gt-js');
const gtWatch = require('./src/config/gulp/gt-watch');

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
		gtCss.css.bind(null, sassSRC, 'style.css', './'),
		gtCss.css.bind(null, adminSassSRC, 'admin.css', 'dist'),
		gulp.series(
			gtJs.lintJS,
			gulp.parallel(gtJs.siteJS, gtJs.pluginsJS, gtJs.vueJS, gtJs.adminJS),
			gtJs.mergeJS,
			gtJs.cleanJS
		),
		gtCss.sasslint,
		gtHtmlLint.htmlLint.bind(null, false),
		gtHtmlLint.htmlLint.bind(null, true)
	),
	gtHtmlLint.htmlw3
));

// build all files for development
gulp.task('build-dev', gulp.series(
	globalVars.createDistFolder,
	devBuild,
	gulp.parallel(
		gtCss.pluginsCss,
		gtCss.css.bind(null, sassSRC, 'style.css', './'),
		gtCss.css.bind(null, adminSassSRC, 'admin.css', 'dist'),
		gulp.series(
			gtJs.lintJS,
			gulp.parallel(gtJs.siteJS, gtJs.pluginsJS, gtJs.vueJS, gtJs.adminJS),
			gtJs.mergeJS,
			gtJs.cleanJS
		),
		gtCss.sasslint,
		gtHtmlLint.htmlLint.bind(null, false),
		gtHtmlLint.htmlLint.bind(null, true)
	),
	gtHtmlLint.htmlw3
));

// remove dist folder
gulp.task('reset-dev', resetDev);

function resetDev() {
	return gulp.src('dist', {read: false})
		.pipe(clean());
}

// start dev tasks
gulp.task('watch', gulp.series(
	globalVars.createDistFolder,
	devBuild,
	gulp.parallel(
		gtCss.pluginsCss,
		gtCss.css.bind(null, sassSRC, 'style.css', './'),
		gtCss.css.bind(null, adminSassSRC, 'admin.css', 'dist'),
		gulp.series(
			gtJs.lintJS,
			gulp.parallel(gtJs.siteJS, gtJs.pluginsJS, gtJs.vueJS, gtJs.adminJS),
			gtJs.mergeJS,
			gtJs.cleanJS
		),
		gtCss.sasslint,
		gtHtmlLint.htmlLint.bind(null, false),
		gtHtmlLint.htmlLint.bind(null, true)
	),
	gtHtmlLint.htmlw3,
	gtWatch.watchFiles
));
