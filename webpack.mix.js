const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

let bowerPath = './bower_components';
let npmPath = './node_modules';
let dashboardCssPath = 'public/dashboard/css';

mix.sourceMaps(true, 'source-map')
    .js('resources/js/app.js', 'public/js')
    .js('resources/js/dashboard/app.js', 'public/dashboard/js/app.js')
    .copy('resources/js/dashboard/views', 'public/dashboard/js/views')
    .sass('resources/sass/app.scss', 'public/dashboard/css')
    .sass('resources/sass/dashboard/style.scss', 'public/dashboard/css')
    .copy(npmPath + '/highcharts/highcharts.js', 'public/js')
    .sass(npmPath + '/highcharts/css/highcharts.scss', dashboardCssPath + '/highcharts.min.css')
;
