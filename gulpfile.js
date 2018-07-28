//load dependecies
var gulp             = require('gulp'),
	autoprefixer     = require('autoprefixer'),
	notify           = require('gulp-notify'),
	plumber          = require('gulp-plumber'),
	postcss          = require('gulp-postcss'),
	iconfont         = require('gulp-iconfont'),
	iconfontCss      = require('gulp-iconfont-css'),
	sourcemaps       = require('gulp-sourcemaps'),
	svgmin           = require('gulp-svgmin');
	gutil            = require('gulp-util'),
	sass             = require('gulp-sass'),
	sassLint         = require('gulp-sass-lint'),
	path             = require('path'),
	flexBugsFix      = require('postcss-flexbugs-fixes'),
	concat			 = require('gulp-concat'),
	concatCss		 = require('gulp-concat-css'),
	cleanCss		 = require('gulp-clean-css'),
	babel            = require('gulp-babel'),
	browserify       = require('browserify'),
	source           = require('vinyl-source-stream'),
	buffer           = require('vinyl-buffer'),
	gulpSequence     = require('gulp-sequence'),
	clean            = require('gulp-clean'),
	uglify			 = require('gulp-uglify');


// build
gulp.task('build', ['plugins-css', 'css', 'js']);
gulp.task('build-prod', ['plugins-css', 'css-prod', 'js-prod']);

//icon fonts
gulp.task('fonticons', function() {
	return gulp.src(['assets/svg/*.svg'])
		.pipe(iconfontCss({
			fontName: 'fonticons',
			cssClass: 'font',
			path: 'assets/config/icon-font-config.scss',
			targetPath: '../../assets/sass/base/_icon-font.scss',
			fontPath: 'assets/icons/'
		}))
		.pipe(iconfont({
			fontName: 'fonticons', // required
			prependUnicode: true, // recommended option
			formats: ['woff2', 'woff', 'ttf'], // default, 'woff2' and 'svg' are available
			normalize: true,
			centerHorizontally: true
		}))
		.on('glyphs', function(glyphs, options) {
			// CSS templating, e.g.
			console.log(glyphs, options);
		})
		.pipe(gulp.dest('assets/icons/'))
});


//error notification settings for plumber
var msgERROR = {
	errorHandler: notify.onError({
		title: 'Fix that ERROR:',
		message: "<%= error.message %>",
		time: 2000
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
});

//styles
gulp.task('css', function() {
	var prefix = [
		autoprefixer({ browsers: ['last 3 versions', 'ios >= 6'] }),
		flexBugsFix
	];
	return gulp.src(['assets/sass/**/*.scss'])
		.pipe(plumber(msgERROR))
		.pipe(sourcemaps.init())
		.pipe(sass({outputStyle: 'expanded'}))
		.pipe(postcss(prefix))
		.pipe(sourcemaps.write('.'))
		.pipe(gulp.dest(''));
});

gulp.task('css-prod', function() {
	var prefix = [
		autoprefixer({ browsers: ['last 3 versions', 'ios >= 6'] }),
		flexBugsFix
	];
	return gulp.src(['assets/sass/**/*.scss'])
		.pipe(plumber(msgERROR))
		.pipe(sourcemaps.init())
		.pipe(sass({outputStyle: 'compressed'}))
		.pipe(postcss(prefix))
		.pipe(sourcemaps.write('.'))
		.pipe(gulp.dest(''));
});

gulp.task('plugins-css', function() {
	return gulp.src(['assets/css-plugins/*.css'])
		.pipe(concatCss("plugins.min.css"))
		.pipe(cleanCss())
		.pipe(gulp.dest('dist'))
});

// sass-lint
gulp.task('sass-lint', function () {
	return gulp.src('assets/sass/**/*.scss')
		.pipe(sassLint({
			config: '.sass-lint.yml'
		}))
		.pipe(sassLint.format())
		.pipe(sassLint.failOnError())
});

// script
gulp.task('plugins-js', function() {
	return gulp.src([
		'assets/js/_libs/*.js',
		'assets/js/_plugins/*.js'
	])
		.pipe(concat('plugins.js'))
		.pipe(gulp.dest('dist'));
});

gulp.task('site-js', function() {
	return browserify('assets/js/site.js').bundle()
		.pipe(source('site.js'))
		.pipe(buffer())
		.pipe(babel({presets: ['es2015'], compact: false}))
		.pipe(gulp.dest('dist'));
});

gulp.task('merge-js', function() {
	return gulp.src([
		'dist/plugins.js',
		'dist/site.js'
	])
		.pipe(plumber(msgERROR))
		.pipe(sourcemaps.init())
		.pipe(concat('site.min.js'))
		.pipe(sourcemaps.write('.'))
		.pipe(gulp.dest('dist'));
});

gulp.task('merge-js-prod', function() {
	return gulp.src([
		'dist/plugins.js',
		'dist/site.js'
	])
		.pipe(plumber(msgERROR))
		.pipe(sourcemaps.init())
		.pipe(concat('site.min.js'))
		.pipe(uglify())
		.pipe(sourcemaps.write('.'))
		.pipe(gulp.dest('dist'));
});

gulp.task('clean-js', function () {
	return gulp.src(['dist/plugins.js', 'dist/site.js'], {read: false})
		.pipe(clean());
});

gulp.task('js', function(callback) {
	gulpSequence('site-js', 'plugins-js', 'merge-js', 'clean-js')(callback)
});
gulp.task('js-prod', gulpSequence('site-js', 'plugins-js', 'merge-js-prod', 'clean-js'));


//watch
gulp.task('watch', function() {
	//watch .scss files
	gulp.watch('assets/sass/**/*.scss', ['css', 'sass-lint']);
	//watch site.dev.js
	gulp.watch('assets/js/**/*.js', ['js']);
	//watch added or changed svg files to optimize them
	gulp.watch('assets/svg/*.svg', ['svgomg']);
});
