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
	}
};
