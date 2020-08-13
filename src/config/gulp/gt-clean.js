const gulp = require('gulp');
const clean = require('gulp-clean');
const destDir = 'dist';

/*----------------------------------------------------------------------------------------------
	Clean
 ----------------------------------------------------------------------------------------------*/
function cleanBuildFiles() {
	return gulp.src([
		destDir + '/plugins.js',
		destDir + '/site.js'], {read: false})
		.pipe(clean());
}

// export tasks
module.exports = {
	cleanBuildFiles: cleanBuildFiles
};
