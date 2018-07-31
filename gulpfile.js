/**
 * Caminhos do CSS e Javascript
 */
var origemCSS = 'assets/css/*.css';
var origemPluginsJS = 'assets/js/plugins/*.js';
var origemFuncoesJS = 'assets/js/functions/*.js';
var destino = 'web/';

/**
 * Plugins requeridos no gulp
 */
var gulp = require("gulp");
var cssmin = require('gulp-cssmin');
var rename = require("gulp-rename");
var uglify = require("gulp-uglify");
var concat = require("gulp-concat");
var stripCSS = require('gulp-strip-css-comments');
var stripJS = require('gulp-strip-comments');

/**
 * Tarefa padrão quando executado o comando "gulp"
 */
gulp.task('default', ['minify-css', 'minify-plugins-js', 'minify-functions-js']);

/**
 * Tarefa de monitoração caso algum arquivo seja modificado
 */
gulp.task('watch', function() {
    gulp.watch(origemCSS, ['minify-css']);
    gulp.watch(origemPluginsJS, ['minify-plugins-js']);
    gulp.watch(origemFuncoesJS, ['minify-functions-js']);    
});

/* tarefa que minifica o css */
gulp.task('minify-css', function() {
    gulp.src(origemCSS)
        .pipe(concat('style.min.css'))
        .pipe(stripCSS({ all: true }))
        .pipe(cssmin())
        .pipe(gulp.dest(destino));
});

/* tarefa que minifica os arquivos javascript */
gulp.task('minify-plugins-js', function () {
  return gulp.src(origemPluginsJS)
    .pipe(stripJS())
    .pipe(concat('plugins.min.js'))
    .pipe(uglify())
    .pipe(gulp.dest(destino));
});

/* tarefa que minifica os arquivos javascript */
gulp.task('minify-functions-js', function () {
  return gulp.src(origemFuncoesJS)
    .pipe(stripJS())
    .pipe(concat('functions.min.js'))
    .pipe(uglify())
    .pipe(gulp.dest(destino));
});