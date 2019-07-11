const fs = require('fs');
const notify = require('gulp-notify');

module.exports = {
	productionBuild: false,
	distAssets: [],
	warningTemp: 'assets/config/warning-log-temp.txt',
	msgERROR: {
		errorHandler: notify.onError({
			title: 'Please, fix the ERROR below:',
			message: '<%= error.message %>',
			time: 2000
		})
	},
	rf(src, callback) {
		fs.readFile(src, 'utf8', function (err, data) {
			if (!err) {
				callback(data);
			} else {
				console.log('ERROR: ', err);
			}
		});
	},
	logMSG(template, str, color = 'yellow') {
		module.exports.rf(template, function (data) {
			data = data.replace(new RegExp('@{str}', 'g'), str);
			color = color === 'yellow' ? '\x1b[33m' : '\x1b[32m';

			console.log(color);
			console.log(data);
			console.log('\x1b[37m');
		});
	},
	createDistFolder(done) {
		if (!fs.existsSync('dist')) {
			fs.mkdirSync('./dist');
		}
		done();
	}
};
