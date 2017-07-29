//load dependecies
var gulp             = require('gulp'),
	autoprefixer     = require('autoprefixer'),
	iconfont         = require('gulp-iconfont'),
	iconfontCss      = require('gulp-iconfont-css'),
	notify           = require('gulp-notify'),
	plumber          = require('gulp-plumber'),
	postcss          = require('gulp-postcss'),
	sass             = require('gulp-sass'),
	sassLint         = require('gulp-sass-lint'),
	size             = require('gulp-size'),
	sourcemaps       = require('gulp-sourcemaps'),
	svgmin           = require('gulp-svgmin');
	gutil            = require('gulp-util'),
	path             = require('path'),
	flexBugsFix      = require('postcss-flexbugs-fixes');

//icon fonts
gulp.task('iconfont', function() {
  return gulp.src(['assets/svg/*.svg'])
	.pipe(iconfontCss({
		fontName: 'svgicons',
		cssClass: 'font',
		path: 'config/icon-font-config.scss',
		targetPath: '../../sass/base/_icon-font.scss',
		fontPath: '../icons/'
	}))
	.pipe(iconfont({
		fontName: 'svgicons', // required
		prependUnicode: false, // recommended option
		formats: ['ttf', 'woff'], // default, 'woff2' and 'svg' are available
		normalize: true,
		centerHorizontally: true
	}))
	.on('glyphs', function(glyphs, options) {
		// CSS templating, e.g.
		console.log(glyphs, options);
	})
	.pipe(gulp.dest('assets/icons/'));
});

//the title and icon that will be used for the Gulp notifications
var notifySVGOMG = {
	title: 'Awesome!',
	message: 'SVG files are optimized!',
	icon: path.join(__dirname, 'config/notify-success.png'),
	time: 1500,
	sound: true
};

var notifyStyles = {
	title: 'Good job :)',
	message: 'Styles are compiled!',
	icon: path.join(__dirname, 'config/notify-success.png'),
	time: 1500,
	sound: false,
	onLast: true
};

//error notification settings for plumber
var plumberErrorHandler = {
	errorHandler: notify.onError({
		title: 'Fix this ERROR, Bitch:',
		message: "<%= error.message %>",
		icon: path.join(__dirname, 'config/notify-error.png'),
		time: 2000,
	})
};

// SVG optimization
gulp.task('svgomg', function () {
	return gulp.src('assets/svg/*.svg')
		.pipe(svgmin({
			plugins: [
				{ removeTitle: true },
				{ removeRasterImages: true },
				{ sortAttrs: true }
				//{ removeStyleElement: true }
			]
		}))
		.pipe(gulp.dest('assets/svg'))
		.pipe(notify(notifySVGOMG));
});

//styles
gulp.task('styles', function() {
	var processors = [
		autoprefixer({ browsers: ['last 3 versions', 'ios >= 6'] }),
		flexBugsFix
	];
	return gulp.src(['sass/**/*.scss'])
		.pipe(plumber(plumberErrorHandler))
		.pipe(sourcemaps.init())
		.pipe(sass({outputStyle: 'compressed'}))
		.pipe(postcss(processors))
		.pipe(sourcemaps.write('.'))
		.pipe(gulp.dest(''))
		.pipe(notify(notifyStyles));
});

gulp.task('sasslint', function () {
  return gulp.src('sass/**/*.scss')
    .pipe(sassLint({
      config: '.sass-lint.yml'
    }))
    .pipe(sassLint.format())
    .pipe(sassLint.failOnError())
});

//watch
gulp.task('default', function() {
	//watch .scss files
	gulp.watch('sass/**/*.scss', ['styles', 'sasslint']);
	//watch added or changed svg files to optimize them
	gulp.watch('assets/svg/*.svg', ['svgomg']);
});
