const fs = require('fs');
const gulp = require('gulp');
const clean = require('gulp-clean');
const gulpVars = require('./gulp-variables');

/*----------------------------------------------------------------------------------------------
	Config
 ----------------------------------------------------------------------------------------------*/
module.exports = {
	/** Create dist folder.. */
	createDistFolder: function(done) {
		if (!fs.existsSync('dist')) {
			fs.mkdirSync('./dist');
		}
		done();
	},

	/** Delete dist folder. */
	deleteDistFolder: function() {
		return gulp.src('dist', {read: false})
			.pipe(clean());
	},

	/** Set for production build. */
	prodBuild: function(done) {
		gulpVars.productionBuild = true;
		done();
	},
	/** Set for development build. */
	devBuild: function(done) {
		gulpVars.productionBuild = false;
		done();
	},
	/** Skip Gulp task. */
	skipBuild: function(done) {
		done();
	},

	/** Run all Gulp tasks and build files. */
	buildFiles: function(env, gulpTasks, done) {
		const _this = this;
		const generateEnqueueYml = env === 'prod' ? this.generateEnqueueYml : this.skipBuild;
		const buildType = env === 'prod' ? this.prodBuild : this.devBuild;
		const watchMode = env === 'watch' ? gulpTasks.gtWatch.watchFiles : this.skipBuild;

		return gulp.series(
			generateEnqueueYml,
			_this.createDistFolder,
			buildType,
			gulp.parallel(
				gulpTasks.gtCss.css.bind(null, gulpVars.scssSiteSRC, 'site'),
				gulpTasks.gtCss.css.bind(null, gulpVars.scssAdminSRC, 'admin'),
				gulp.series(
					gulpTasks.gtJs.lintJS,
					gulpTasks.gtJs.js.bind(null, [gulpVars.jsSiteSRC, gulpVars.jsAdminSRC])
				),
				gulpTasks.gtCss.sasslint,
				gulpTasks.gtMjml.copyHtmlFiles,
				gulpTasks.gtMjml.compileMjml,
				gulpTasks.gtHtmlLint.htmlLint.bind(null, false),
				gulpTasks.gtHtmlLint.htmlLint.bind(null, true)
			),
			watchMode
		)(done);
	},

	/** Generate .fwsenqueue.yml file. */
	generateEnqueueYml: function(done) {
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
};
