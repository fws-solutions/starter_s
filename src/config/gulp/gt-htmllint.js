const gulp = require('gulp');
const htmllint = require('gulp-htmllint');
const fancyLog = require('fancy-log');
const colors = require('ansi-colors');
const axios = require('axios');
const validator = require('html-validator');

/*----------------------------------------------------------------------------------------------
	W3 Validator
 ----------------------------------------------------------------------------------------------*/
gulp.task('html-w3', htmlw3);

const gulpfile = require('../../../gulpfile');

function htmlw3(done) {
	getPages(gulpfile.localURL, done);
}

function ValidatorConfig(url) {
	this.url = url;
	this.format = 'text';
	this.isLocal = true;
	this.ignore = [
		'Warning: The "type" attribute is unnecessary for JavaScript resources.'
	];
}

async function getPages(url, done) {
	const pagesURL = `${url}/wp-json/wp/v2/pages?per_page=100`;
	const postsURL = `${url}/wp-json/wp/v2/posts?per_page=5`;

	try {
		const pages = await axios.get(pagesURL);
		const posts = await axios.get(postsURL);
		const all = pages.data.concat(posts.data);

		all.forEach((cur) => {
			validateHTML(cur.link);
		});

		done();
	} catch (error) {
		console.error(error);
		done();
	}
}

async function validateHTML(url) {
	const config = new ValidatorConfig(url);

	try {
		let counter = 0;
		let result = await validator(config);
		const hasErrorsString = 'There were errors.';

		if (result.includes(hasErrorsString)) {
			result = result.replace('\n' + hasErrorsString, '').split('\n');

			await console.log('\n\n');
			await console.log(colors.cyan(`########## W3 Validator Error on Page: ${url} ##########`));
			await console.log('\n');

			result.forEach((cur, i) => {
				if (i % 2 !== 0) {
					fancyLog(colors.yellow(`Location: ${cur}`));
					fancyLog(colors.cyan('-----------------------------------------------------'));
					console.log('\n');
				} else {
					counter++;
					fancyLog(colors.cyan(`No.#${counter} -----------------------------------------------`));
					fancyLog(colors.red(cur));
				}
			});
		}
	} catch (error) {
		console.error(error);
	}
}

/*----------------------------------------------------------------------------------------------
	HTML Lint
 ----------------------------------------------------------------------------------------------*/
gulp.task('html-lint-fe', htmlLint.bind(null, true));
gulp.task('html-lint-be', htmlLint.bind(null, false));

function LintConfig(isFe = true) {
	if (!isFe) {
		this['attr-req-value'] = false;
		this['attr-name-style'] = false;
		this['attr-validate'] = false;
		this['attr-no-dup'] = false;
		this['indent-style'] = false;
		this['indent-width'] = false;
		this['class-style'] = false;
		this['attr-order'] = false;
		this['class-no-dup'] = false;
	} else {
		this['class-style'] = 'bem';
		this['attr-order'] = ['class', 'id', 'href', 'src', 'target', 'title', 'name', 'value', 'alt', 'selected', 'checked', 'required', 'disabled'];
	}

	this['line-end-style'] = false;
	this['indent-style'] = false;
	this['img-req-alt'] = 'allownull';
	this['id-class-no-ad'] = false;
	this['id-class-style'] = false;
	this['spec-char-escape'] = false;
	this['attr-bans'] = ['align', 'background', 'bgcolor', 'border', 'frameborder', 'longdesc', 'marginwidth', 'marginheight', 'scrolling', 'width'];
}

function htmlLint(isFe) {
	const configRules = new LintConfig(isFe);
	const htmlSrc = isFe ? 'template-views/**/_*.php' : ['template-views/**/*.php', '!template-views/**/_*.php'];

	return gulp.src(htmlSrc)
		.pipe(htmllint({
			rules: configRules
		}, htmllintReporter));
}

function htmllintReporter(filepath, issues) {
	if (issues.length > 0) {
		issues.forEach(function(issue) {
			filepath = filepath.split('/wp-content/themes/').pop();

			fancyLog(colors.cyan('[gulp-htmllint] ') + colors.yellow('\n file:  ' + filepath + ' [' + issue.line + ',' + issue.column + ']: ') + colors.red('\n error: ' + issue.rule + ' --- ' + issue.msg + '\n----------------------------------------------------'));
		});

		process.exitCode = 1;
	}
}

// export tasks
module.exports = {
	htmlw3: htmlw3,
	htmlLint: htmlLint
};