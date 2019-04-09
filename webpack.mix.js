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

mix.js('resources/js/app.js', 'public/js')
   .sass('resources/sass/app.scss', 'public/css')
   .copy('node_modules/jsqr/dist/jsQR.js', 'public/js')
   .copy('resources/js/qrcode.min.js', 'public/js')
   .scripts([
      'node_modules/datatables.net/js/jquery.dataTables.min.js',
      'node_modules/datatables.net-bs4/js/dataTables.bootstrap4.min.js'
  ], 'public/js/datatables.js')
  .styles([
      'node_modules/datatables.net-bs4/css/dataTables.bootstrap4.min.css'
  ], 'public/css/bs4-datatables.css')
  .scripts([
      'node_modules/sweetalert2/dist/sweetalert2.all.min.js',
  ], 'public/js/sweetalert2.js')
  .styles([
      'node_modules/sweetalert2/dist/sweetalert2.min.css',
  ], 'public/css/sweetalert2.css');