//load dependecies
var gulp             = require('gulp'),
	autoprefixer     = require('autoprefixer'),
	notify           = require('gulp-notify'),
	plumber          = require('gulp-plumber'),
	postcss          = require('gulp-postcss'),
	iconfont         = require('gulp-iconfont'),
	iconfontCss      = require('gulp-iconfont-css'),
	size             = require('gulp-size'),
	sourcemaps       = require('gulp-sourcemaps'),
	svgmin           = require('gulp-svgmin');
	gutil            = require('gulp-util'),
	sass             = require('gulp-sass'),
	sassLint         = require('gulp-sass-lint'),
	path             = require('path'),
	flexBugsFix      = require('postcss-flexbugs-fixes'),
	filter 			 = require('gulp-filter'),
	concat			 = require('gulp-concat'),
	concatCss		 = require('gulp-concat-css'),
	cleanCss		 = require('gulp-clean-css'),
	uglify			 = require('gulp-uglify');
	ftp 			 = require('vinyl-ftp');


// ftp
gulp.task('deploy', function() {
    var conn = ftp.create({
        host:     'webdizajnsrbija.net',
        user:     'nidzan@webdizajnsrbija.net',
        password: 'K[UVPDaAbAhF',
        parallel: 10,
        log:      gutil.log
    });

    var globs = [
		'./**/*',
		'!node_modules',
		'!node_modules/**',
        '!.git/**',
        '!.idea/**'
    ];

    // using base = '.' will transfer everything to /public_html correctly
    // turn off buffering in gulp.src for best performance

    return gulp.src( globs, { base: '.', buffer: false } )
        .pipe( conn.newer( '/nikolatopalovic.net/wptest/wp-content/themes/starter_s' ) ) // only upload newer files
        .pipe( conn.dest( '/nikolatopalovic.net/wptest/wp-content/themes/starter_s' ) );
} );


gulp.task('load-css', function() {
	return gulp.src(['assets/css/*.css'])
	.pipe(concatCss("plugins.min.css"))
	.pipe(cleanCss())
	.pipe(gulp.dest('dist'))
});

gulp.task('load-js', function() {
	return gulp.src(['assets/js/_plugins/*.js'])
	.pipe(concat('plugins.min.js'))
	.pipe(uglify())
	.pipe(gulp.dest('dist'))
});

gulp.task('load-plugins', ['load-css', 'load-js']);



//icon fonts
gulp.task('iconfont', function() {
  return gulp.src(['assets/svg/*.svg'])
	.pipe(iconfontCss({
		fontName: 'fonticons',
		cssClass: 'font',
		path: 'config/icon-font-config.scss',
		targetPath: '../../sass/base/_icon-font.scss',
		fontPath: '../icons/'
	}))
	.pipe(iconfont({
		fontName: 'fonticons', // required
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
var msgSVG = {
	title: 'Great!',
	message: 'SVG files optimized!',
	icon: path.join(__dirname, 'config/notify-success.png'),
	time: 1500,
	sound: true
};

var msgSASS = {
	title: 'Sweet :)',
	message: 'Styles are compiled!',
	icon: path.join(__dirname, 'config/notify-success.png'),
	time: 1500,
	sound: false,
	onLast: true
};

var msgJS = {
    title: 'Awesome :)',
    message: 'Script is compiled!',
    icon: path.join(__dirname, 'config/notify-success.png'),
    time: 1500,
    sound: false,
    onLast: true
};

//error notification settings for plumber
var msgERROR = {
	errorHandler: notify.onError({
		title: 'Fix that ERROR, bitch:',
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
		.pipe(notify(msgSVG));
});

//styles
gulp.task('styles', function() {
	var prefix = [
		autoprefixer({ browsers: ['last 3 versions', 'ios >= 6'] }),
		flexBugsFix
	];
	return gulp.src(['sass/**/*.scss'])
		.pipe(plumber(msgERROR))
		.pipe(sourcemaps.init())
		.pipe(sass({outputStyle: 'compressed'}))
		.pipe(postcss(prefix))
		.pipe(sourcemaps.write('.'))
		.pipe(gulp.dest(''))
		.pipe(notify(msgSASS));
});

gulp.task('sass-lint', function () {
  return gulp.src('sass/**/*.scss')
    .pipe(sassLint({
      config: '.sass-lint.yml'
    }))
    .pipe(sassLint.format())
    .pipe(sassLint.failOnError())
});

// script
gulp.task('script', function() {
    return gulp.src(['assets/js/site.dev.js'])
        .pipe(plumber(msgERROR))
        .pipe(sourcemaps.init())
        .pipe(concat('site.min.js'))
        .pipe(uglify())
        .pipe(sourcemaps.write('.'))
        .pipe(gulp.dest('dist'))
        .pipe(notify(msgJS));
});

//watch
gulp.task('watch', function() {
	//watch .scss files
	gulp.watch('sass/**/*.scss', ['styles', 'sass-lint']);
	//watch site.dev.js
    gulp.watch('assets/js/site.dev.js', ['script']);
	//watch added or changed svg files to optimize them
	gulp.watch('assets/svg/*.svg', ['svgomg']);
});
