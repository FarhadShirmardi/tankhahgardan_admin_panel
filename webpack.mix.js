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
    .copy(bowerPath + '/jquery/dist/jquery.min.js', 'public/js')
    .copy(npmPath + '/@popperjs/core/dist/umd/popper.min.js', 'public/js')
    .copy(bowerPath + '/bootstrap/dist/js/bootstrap.min.js', 'public/js')
    .copy(bowerPath + '/pace/pace.min.js', 'public/js')
    .copy(bowerPath + '/jstree/dist/themes/default/32px.png', dashboardCssPath)
    .copy(bowerPath + '/jstree/dist/themes/default/40px.png', dashboardCssPath)
    .copy(bowerPath + '/jstree/dist/themes/default/throbber.gif', dashboardCssPath)
    .styles([bowerPath + '/jstree/dist/themes/default/style.min.css'], dashboardCssPath + '/jstree.min.css')
    .copy(bowerPath + '/pace/pace.min.js', 'public/js')
    .copy(bowerPath + '/jstree/dist/jstree.min.js', 'public/js')
    .copy(bowerPath + '/select2/dist/js/select2.full.min.js', 'public/js')
    .copy(bowerPath + '/select2/dist/js/i18n', 'public/js/i18n')
    .styles([bowerPath + '/select2/dist/css/select2.min.css'], dashboardCssPath + '/select2.min.css')
    .copy(bowerPath + '/jquery-validation/dist/jquery.validate.min.js', 'public/js')
    .copy(npmPath + '/cropper/dist/cropper.min.js', 'public/js')
    .styles([npmPath + '/cropper/dist/cropper.min.css'], dashboardCssPath + '/cropper.min.css')
    .copy(bowerPath + '/dropzone/dist/min/dropzone.min.js', 'public/js')
    .styles([bowerPath + '/dropzone/dist/min/dropzone.min.css'], dashboardCssPath + '/dropzone.min.css')
    .copy(bowerPath + '/jscolor-picker/jscolor.min.js', 'public/js')
    .copy(bowerPath + '/persian-date/dist/persian-date.min.js', 'public/js')
    .copy(bowerPath + '/persian-datepicker/dist/js/persian-datepicker.min.js', 'public/js')
    .styles(
        [bowerPath + '/persian-datepicker/dist/css/persian-datepicker.min.css'],
        dashboardCssPath + '/persian-datepicker.min.css'
    )
    .copy(bowerPath + '/gasparesganga-jquery-loading-overlay/dist/loadingoverlay.min.js', 'public/js')
    .copy(bowerPath + '/jquery-toast-plugin/dist/jquery.toast.min.js', 'public/js')
    .copy('./resources/js/dashboard/tinymce/fa.js', 'public/js')
    .styles([bowerPath + '/jquery-toast-plugin/dist/jquery.toast.min.css'], dashboardCssPath + '/jquery.toast.min.css')
;
