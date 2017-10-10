var gulp = require('gulp'),
    jshint = require('gulp-jshint'),
    sass = require('gulp-ruby-sass'),
    sourcemaps = require('gulp-sourcemaps');//,
    //webserver = require('gulp-webserver');

gulp.task('js', function() {
  return gulp.src('./www/public/resources/js/myscript.js')
    .pipe(jshint('./.jshintrc'))
    .pipe(jshint.reporter('jshint-stylish'));
});

gulp.task('sass', function () {
    return sass('./www/public/resources/sass/style.sass', {
      sourcemap: true,
      style: 'compressed'
    })
    .on('error', function (err) {
        console.error('Error!', err.message);
    })
    .pipe(sourcemaps.write())
    .pipe(gulp.dest('./www/public/resources/css'));
});

gulp.task('watch', function() {
  //gulp.watch('resources/js/**/*', ['js']);
  gulp.watch(['./www/public/resources/sass/**/*'], ['sass']);
});

/*
gulp.task('webserver', function() {
    gulp.src('')
        .pipe(webserver({
            livereload: true,
            open: true
        }));
});*/

gulp.task('default', ['sass', 'watch']);
