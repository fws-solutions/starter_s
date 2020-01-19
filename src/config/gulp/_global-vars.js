const notify = require('gulp-notify');
const fs = require('fs');

module.exports = {
	productionBuild: false,
	distAssets: [],
	msgERROR: {
		errorHandler: notify.onError({
			title: 'Please, fix the ERROR below:',
			message: '<%= error.message %>',
			time: 2000
		})
	},
	createDistFolder(done) {
		if (!fs.existsSync('dist')) {
			fs.mkdirSync('./dist');
		}
		done();
	}
};
