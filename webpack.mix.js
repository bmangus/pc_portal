const mix = require('laravel-mix');
//const LiveReloadPlugin = require('webpack-livereload-plugin');
const tailwindcss = require('tailwindcss');
const domain = 'pc_portal.test'; // <== edit this one
const homedir = require('os').homedir();

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
    .options({
        processCssUrls: false,
        postCss: [ tailwindcss('./tailwind.config.js')]
    })
    .browserSync({
        proxy: 'https://' + domain,
        host: domain,
        open: 'external',
        https: {
            key: homedir + '/.config/valet/Certificates/' + domain + '.key',
            cert: homedir + '/.config/valet/Certificates/' + domain + '.crt',
        },
    })
    .copy('storage/fonts', 'public/storage/fonts');
