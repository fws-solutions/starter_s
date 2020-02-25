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
const localURL = 'http://starter.local/';
const sassSRC = ['src/scss/**/*.scss', 'template-views/**/**/*.scss'];
const adminSassSRC = ['src/config/admin/scss/admin.scss'];

module.exports = {
	localURL: localURL
};

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
		gtHtmlLint.htmlLint.bind(null, true),
		gtHtmlLint.htmlw3
	)
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
		gtHtmlLint.htmlLint.bind(null, true),
		gtHtmlLint.htmlw3
	)
));

// remove dist folder
gulp.task('reset-dev', resetDev);

function resetDev() {
	return gulp.src('dist', {read: false})
		.pipe(clean());
}

// remove all _fe files in template-views dir
gulp.task('remove-fe', removeFeFiles);

function removeFeFiles() {
	console.log(colors.red(`DELETED FE FILES:`));

	return gulp.src('template-views/**')
		.pipe(tap(function(file) {
			const filePath = file.path;
			const fileStat = fs.lstatSync(filePath);
			const fileName = path.basename(filePath);

			if (!fileStat.isDirectory() && path.extname(filePath) === '.php' && fileName.substring(0, 4) === '_fe-') {
				fs.unlinkSync(filePath);
				console.log(colors.red(fileName));
			}
		}));
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
		gtHtmlLint.htmlLint.bind(null, true),
		gtHtmlLint.htmlw3
	),
	gtWatch.watchFiles
));
