const gulp = require('gulp');
const htmllint = require('gulp-htmllint');
const fancyLog = require('fancy-log');
const colors = require('ansi-colors');

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
	htmlLint: htmlLint
};
