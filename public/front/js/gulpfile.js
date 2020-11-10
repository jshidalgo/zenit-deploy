// 1. npm install
// 2. npm install -g gulp
// 3.npm install  --save  gulp-sass gulp-less gulp-notify gulp-plumber


var gulp = require('gulp');
var sass = require('gulp-sass');
var notify =  require("gulp-notify");
var plumber =  require('gulp-plumber');

var onError = function (err) {
    notify({
        title: 'Gulp Task Error',
        message: 'Check the console.'
    }).write(err);
    console.log(err.toString());
    this.emit('end');
};

var sassFile="../sass/styles.scss" , cssDest="../css/";
gulp.task('sass', function() {
    return gulp
        .src(sassFile)
        .pipe(plumber({ errorHandle: onError }))
        .pipe(sass())
        .on('error', onError)
        .pipe(gulp.dest(cssDest))
        .pipe(notify({
            message: 'SASS complete'
        }))
    ;
});

gulp.task(
    'default',
    gulp.series('sass', function() {
        gulp.watch('../sass/**/*.scss', gulp.series('sass'));
    })
);



