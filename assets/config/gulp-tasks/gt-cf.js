const gulp = require('gulp');
const fs = require('fs');
const argv = require('yargs').argv;
const globalVars = require('./_global-vars');

/*----------------------------------------------------------------------------------------------
	Create/Read/Update Files
 ----------------------------------------------------------------------------------------------*/
function createFiles(arg, type) {
	const part = `${type}s`;
	let directory = `template-views/${part}/${arg}`;

	function create(file, isFE = false) {
		let temp = `${type}-php-temp.txt`;
		let filename;

		// detect which file to create or update
		if (file === 'scss') {
			temp = `${type}-scss-temp.txt`;
			filename = `_${arg}.scss`;
		} else if (file === 'php' && isFE) {
			temp = `${type}-fe-php-temp.txt`;
			filename = `_fe-${arg}.php`;
		} else if (file === 'php') {
			filename = `${arg}.php`;
		}

		const styleSRC = `assets/sass/layout/_${part}.scss`;
		const readDir = file === 'style' ? styleSRC : `assets/config/cf-templates/${temp}`;
		const writeDir = file === 'style' ? styleSRC : `${directory}/${filename}`;

		globalVars.rf(readDir, function (data) {
			let output;

			if (file === 'style') {
				output = data + `\n@import '../../../template-views/${part}/${arg}/${arg}';`;
			} else {
				output = data.replace(new RegExp(`@{${type}}`, 'g'), arg);
			}

			fs.writeFileSync(writeDir, output);
		});
	}

	// create if template or module doesn't exists
	if (!fs.existsSync(directory)) {
		fs.mkdirSync(directory);

		create('php');
		create('php', true);
		create('scss');
		create('style');

		globalVars.logMSG(`assets/config/cf-templates/${type}-log-temp.txt`, arg, 'green');
	} else {
		globalVars.logMSG(globalVars.warningTemp, `ERROR: ${type} '${arg}' already exists`);
	}
}

function cf(done) {
	if (argv.component && typeof argv.component === 'string') {
		// create component TWIG, JSON and SCSS files
		createFiles(argv.component.toLowerCase(), 'component');
	} else if (argv.partial && typeof argv.partial === 'string') {
		// create partial TWIG and SCSS files
		createFiles(argv.partial.toLowerCase(), 'partial');
	} else {
		globalVars.logMSG(globalVars.warningTemp, 'ERROR: no parameters were passed');
	}

	done();
}

gulp.task('cf', cf);
