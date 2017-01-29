"use strict";

var gulp = require('gulp'),
    flatten = require('gulp-flatten'),
    sass = require('gulp-sass'),
    clean = require("gulp-clean"),
    cssmin = require('gulp-cssmin'),
    jsmin = require("gulp-jsmin"),
    rename = require("gulp-rename");

var assets_dir = './../../web/assets/';

gulp.task('build', ['build:css:move', 'build:js:move', 'build:fonts:move'], function () {
    console.log('Project has been builded!');
});

gulp.task('build:css', ['build:sass'], function () {
    var stream = gulp.src([
            './styles/*.css',
            '!./styles/*min.css'
        ])
        .pipe(cssmin())
        .pipe(rename({suffix: '.min'}))
        .pipe(gulp.dest('./styles/'));

    console.log('CSS Done!');

    return stream;
});

gulp.task('build:js', function () {
    var stream = gulp.src([
            './js/*.js',
            '!./js/*min.js'
        ])
        .pipe(jsmin())
        .pipe(rename({suffix: '.min'}))
        .pipe(gulp.dest('./js/'));

    console.log('CSS Done!');

    return stream;
});

gulp.task('build:sass', function() {
    return gulp.src('./styles/sass/**/*.scss')
        .pipe(sass({outputStyle: 'expanded'}))
        .pipe(gulp.dest('./styles/'))
});

gulp.task('build:css:move', ['build:css'], function () {
    var stream = gulp.src([
            './bower_components/*/dist/**/*.min.css',
            './styles/*.min.css'
        ])
        .pipe(flatten())
        .pipe(gulp.dest(assets_dir + 'css/'));

    console.log('CSS Moved!');

    return stream;
});

gulp.task('build:js:move', ['build:js'], function () {
    var stream = gulp.src([
            './bower_components/*/dist/**/*.min.js',
            './js/*.min.js'
        ])
        .pipe(flatten())
        .pipe(gulp.dest(assets_dir + '/js/'));

    console.log('JS moved!');

    return stream;
});

gulp.task('build:fonts:move', function () {
    var stream = gulp.src([
            './bower_components/*/dist/**/*.eot',
            './bower_components/*/dist/**/*.svg',
            './bower_components/*/dist/**/*.ttf',
            './bower_components/*/dist/**/*.woff',
            './bower_components/*/dist/**/*.woff2'
        ])
        .pipe(flatten())
        .pipe(gulp.dest(assets_dir + 'fonts/'));

    console.log('Fonts moved!');

    return stream;
});
