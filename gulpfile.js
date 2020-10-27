const gulp = require('gulp');
const clean = require('gulp-clean');
const globalVars = require('./src/config/gulp/_global-vars');
const fs = require('fs');

/*----------------------------------------------------------------------------------------------
	Prepare and Run all Gulp Tasks
 ----------------------------------------------------------------------------------------------*/
const sassSRC = ['src/scss/**/*.scss', 'template-views/**/**/*.scss'];
const adminSassSRC = ['src/config/admin/scss/admin.scss'];

const gtHtmlLint = require('./src/config/gulp/gt-htmllint');
const gtCss = require('./src/config/gulp/gt-css');
const gtJs = require('./src/config/gulp/gt-js');
const gtWatch = require('./src/config/gulp/gt-watch');
const gtClean = require('./src/config/gulp/gt-clean');

// prepare for build
function prodBuild(done) {
	globalVars.productionBuild = true;
	done();
}

function devBuild(done) {
	globalVars.productionBuild = false;
	done();
}

function generateEnqueueYml(done) {
	const getMonth = function(date) {
		const month = date.getMonth() + 1;
		return month < 10 ? '0' + month : month;
	};
	const date = new Date();
	const version = [
		date.getFullYear(),
		getMonth(date),
		date.getDate(),
		date.getHours(),
		date.getMinutes()
	];
	fs.writeFileSync('.fwsenqueue.yml', 'enqueue-version: ' + version.join('.'));

	done();
}

// build all files for production
gulp.task('build', gulp.series(
	generateEnqueueYml,
	globalVars.createDistFolder,
	prodBuild,
	gulp.parallel(
		gtCss.css.bind(null, sassSRC, 'style.css', './'),
		gtCss.css.bind(null, adminSassSRC, 'admin.css', 'dist'),
		gulp.series(
			gtJs.lintJS,
			gulp.parallel(gtJs.siteJS, gtJs.pluginsJS, gtJs.adminJS),
			gtJs.mergeJS
		),
		gtCss.sasslint,
		gtHtmlLint.htmlLint.bind(null, false),
		gtHtmlLint.htmlLint.bind(null, true)
	),
	gtClean.cleanBuildFiles
));

// build all files for development
gulp.task('build-dev', gulp.series(
	globalVars.createDistFolder,
	devBuild,
	gulp.parallel(
		gtCss.css.bind(null, sassSRC, 'style.css', './'),
		gtCss.css.bind(null, adminSassSRC, 'admin.css', 'dist'),
		gulp.series(
			gtJs.lintJS,
			gulp.parallel(gtJs.siteJS, gtJs.pluginsJS, gtJs.adminJS),
			gtJs.mergeJS
		),
		gtCss.sasslint,
		gtHtmlLint.htmlLint.bind(null, false),
		gtHtmlLint.htmlLint.bind(null, true)
	),
	gtClean.cleanBuildFiles
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
		gtCss.css.bind(null, sassSRC, 'style.css', './'),
		gtCss.css.bind(null, adminSassSRC, 'admin.css', 'dist'),
		gulp.series(
			gtJs.lintJS,
			gulp.parallel(gtJs.siteJS, gtJs.pluginsJS, gtJs.adminJS),
			gtJs.mergeJS
		),
		gtCss.sasslint,
		gtHtmlLint.htmlLint.bind(null, false),
		gtHtmlLint.htmlLint.bind(null, true)
	),
	gtClean.cleanBuildFiles,
	gtWatch.watchFiles
));
