const fs = require('fs');
const gulp = require('gulp');
const notify = require('gulp-notify');
const clean = require('gulp-clean');

/*----------------------------------------------------------------------------------------------
	Config
 ----------------------------------------------------------------------------------------------*/
module.exports = {
	/** Configure global variables. */
	productionBuild: false,
	distAssets: [],
	scssSiteSRC: [
		'src/scss/**/*.scss',
		'template-views/**/**/*.scss',
		'!src/scss/admin.scss',
		'!src/scss/admin/*.scss'
	],
	scssAdminSRC: ['src/scss/admin.scss', 'src/scss/admin/*.scss'],
	scssAllSRC: ['src/scss/**/*.scss', 'template-views/**/**/*.scss'],
	jsSiteSRC: 'src/js/site.js',
	jsAdminSRC: 'src/js/admin.js',
	distSRC: 'dist',
	msgERROR: {
		errorHandler: notify.onError({
			title: 'Please, fix the ERROR below:',
			message: '<%= error.message %>',
			time: 2000
		})
	},

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
		this.productionBuild = true;
		done();
	},
	/** Set for development build. */
	devBuild: function(done) {
		this.productionBuild = false;
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
				gulpTasks.gtCss.css.bind(null, _this.scssSiteSRC, 'site'),
				gulpTasks.gtCss.css.bind(null, _this.scssAdminSRC, 'admin'),
				gulp.series(
					gulpTasks.gtJs.lintJS,
					gulpTasks.gtJs.js.bind(null, [_this.jsSiteSRC, _this.jsAdminSRC])
				),
				gulpTasks.gtCss.sasslint,
				gulpTasks.gtMjml.copyHtmlFiles,
				gulpTasks.gtMjml.compileMjml,
				gulpTasks.gtHtmlLint.htmlLint.bind(null, false),
				gulpTasks.gtHtmlLint.htmlLint.bind(null, true)
			),
			//gulpTasks.gtClean.cleanBuildFiles,
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
