let mix = require('laravel-mix');

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

mix.js('resources/assets/js/app.js', 'public/js').version();

mix.sass('resources/assets/sass/app.scss', 'public/css').version();
mix.browserSync('torrent-theatre.test');

// Or...

// https://browsersync.io/docs/options
mix.browserSync({
    proxy: 'torrent-theatre.test'
});
