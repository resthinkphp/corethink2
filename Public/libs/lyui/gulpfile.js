var gulp         = require('gulp')
var path         = require('path')
var less         = require('gulp-less')
var autoprefixer = require('gulp-autoprefixer')
var sourcemaps   = require('gulp-sourcemaps')
var minifyCss    = require('gulp-minify-css')
var rename       = require('gulp-rename')
var concat       = require('gulp-concat')
var uglify       = require('gulp-uglify')
var connect      = require('gulp-connect')
var open         = require('gulp-open')

//gulp.task('default', ['less', 'js'])

gulp.task('default', ['less', 'js', 'less-extend', 'js-extend'])

gulp.task('docs', ['server'], function () {
  gulp.src(__filename)
    .pipe(open({uri: 'http://localhost:9001/docs/'}))
})

gulp.task('server', function () {
  connect.server({
    root: 'docs',
    port: 9001,
    livereload: true
  })
})

gulp.task('less', function () {
  return gulp.src('./lyui/lyui.less')
    .pipe(sourcemaps.init())
    .pipe(less())
    .pipe(autoprefixer({
        browsers: ['> 5%', 'Android >= 2.3'],
    }))
    .pipe(rename('lyui.css'))
    .pipe(gulp.dest('dist/css'))
    .pipe(minifyCss())
    .pipe(rename({
      suffix: '.min'
    }))
    .pipe(sourcemaps.write('./'))
    .pipe(gulp.dest('dist/css'))
})

gulp.task('js', function () {
  return gulp.src([
      './bootstrap/js/transition.js',
      './bootstrap/js/alert.js',
      './bootstrap/js/affix.js',
      './bootstrap/js/button.js',
      './bootstrap/js/carousel.js',
      './bootstrap/js/collapse.js',
      './bootstrap/js/dropdown.js',
      './bootstrap/js/modal.js',
      './bootstrap/js/tooltip.js',
      './bootstrap/js/popover.js',
      './bootstrap/js/scrollspy.js',
      './bootstrap/js/tab.js',
      './lyui/js/*.js',
    ])
    .pipe(sourcemaps.init())
    .pipe(concat('lyui.js'))
    .pipe(gulp.dest('dist/js'))
    .pipe(uglify())
    .pipe(rename({
      suffix: '.min'
    }))
    .pipe(sourcemaps.write('./'))
    .pipe(gulp.dest('dist/js'))
})



gulp.task('less-extend', function () {
  return gulp.src('./lyui.extend/lyui.extend.less')
    .pipe(sourcemaps.init())
    .pipe(less())
    .pipe(autoprefixer({
        browsers: ['> 5%', 'Android >= 2.3'],
    }))
    .pipe(rename('lyui.extend.css'))
    .pipe(gulp.dest('dist/css'))
    .pipe(minifyCss())
    .pipe(rename({
      suffix: '.min'
    }))
    .pipe(sourcemaps.write('./'))
    .pipe(gulp.dest('dist/css'))
})

gulp.task('js-extend', function () {
  return gulp.src([
      './lyui.extend/js/*.js',
    ])
    .pipe(sourcemaps.init())
    .pipe(concat('lyui.extend.js'))
    .pipe(gulp.dest('dist/js'))
    .pipe(uglify())
    .pipe(rename({
      suffix: '.min'
    }))
    .pipe(sourcemaps.write('./'))
    .pipe(gulp.dest('dist/js'))
})
