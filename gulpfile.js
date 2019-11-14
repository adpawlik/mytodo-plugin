var gulp = require('gulp'),
settings = require('./settings'),
webpack = require('webpack'),
sass = require('gulp-sass'),
browserSync = require('browser-sync').create(),
autoprefixer = require('gulp-autoprefixer'),
cssmin = require('gulp-cssmin'),
rename = require('gulp-rename');

gulp.task('sass', function(){
    return gulp.src(settings.themeLocation + 'assets/scss/style.scss')
    .pipe(sass(
      {
        includePaths: require('node-normalize-scss').includePaths
      }
    )) 
    .on('error', (error) => console.log(error.toString()))
    .pipe(autoprefixer({
        "overrideBrowserslist": ["last 2 versions"],
        cascade: false
    }))
    .pipe(cssmin())
    .pipe(rename('custom.min.css'))
    .pipe(gulp.dest(settings.themeLocation + 'assets/temp/css'))
});

gulp.task('scripts', function(callback){
  webpack(require('./webpack.config.js'), function(err, stats){
    if(err){
      console.log(err.toString());
    }
    console.log(stats.toString());
    callback();
  });
});

//
gulp.task('watch', function (){
  browserSync.init({
    notify: false,
    proxy: settings.urlToPreview,
/*     server: {
      baseDir: 'app'
    }, */
    ghostMode: false
  });

  gulp.watch(settings.themeLocation + '**/*.php', function() {
    browserSync.reload();
  });
  gulp.watch(settings.themeLocation + '**/*.html', function() {
    browserSync.reload();
  });

  gulp.watch(settings.themeLocation + 'assets/scss/**/*.scss', gulp.parallel('waitForStyles')); 
  gulp.watch([settings.themeLocation + "assets/js/modules/*.js", settings.themeLocation + "assets/js/*.js"], gulp.parallel('waitForScripts'));
});


gulp.task('waitForStyles', gulp.series('sass', function() {
  return gulp.src(settings.themeLocation + 'assets/temp/css/custom.min.css')
    .pipe(browserSync.stream());
}))

gulp.task('waitForScripts', gulp.series('scripts', function(cb) {
  browserSync.reload();
  cb()
}))