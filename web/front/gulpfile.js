const gulp         = require('gulp');
const less         = require('gulp-less');
const cssnano      = require('gulp-cssnano');
const autoprefixer = require('gulp-autoprefixer');

gulp.task('less', function () {
    return gulp.src('less/main.less')
        .pipe(less())
        .pipe(autoprefixer(['last 15 versions', '> 1%', 'ie 9'], { cascade: true }))
        .pipe(cssnano())
        .pipe(gulp.dest('../public/css'))
});

gulp.task('watch', ['less'], function () {
    gulp.watch('less/**/*.less', ['less']);
});
