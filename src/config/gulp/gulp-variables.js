const notify = require('gulp-notify');

/*----------------------------------------------------------------------------------------------
	Config
 ----------------------------------------------------------------------------------------------*/
module.exports = {
	/** Configure global variables. */
	productionBuild: false,
	distAssets: [],
	scssSiteSRC: [
		'src/scss/**/*.scss',
		'template-views/parts/**/*.scss',
		'template-views/listings/**/*.scss',
		'!src/scss/admin.scss',
		'!src/scss/admin/*.scss'
	],
	scssBlocksSRC: [
		'template-views/blocks/**/*.scss'
	],
	scssAdminSRC: ['src/scss/admin.scss', 'src/scss/admin/*.scss'],
	scssGutenSRC: ['src/scss/gutenberg.scss', 'src/scss/admin/*.scss'],
	scssAllSRC: ['src/scss/**/*.scss', 'template-views/**/**/*.scss'],
	jsSiteSRC: 'src/js/site.js',
	jsAdminSRC: 'src/js/admin.js',
	jsBlocksSCR: 'template-views/blocks/**/*.js',
	distSRC: 'dist',
	msgERROR: {
		errorHandler: notify.onError({
			title: 'Please, fix the ERROR below:',
			message: '<%= error.message %>',
			time: 2000
		})
	}
};
