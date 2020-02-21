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

mix.js('resources/assets/js/app.js', 'dist/relate.js')
    .styles('resources/assets/css/app.css', 'dist/relate.css')
;

const nodeenv= process.env.NODE_ENV;
const merge= require('webpack-merge');
let commonConfig= {};

if(nodeenv === 'development'){
    commonConfig= merge(commonConfig, require('./webpack.mix.dev.js'));
}else if(nodeenv === 'production'){
    commonConfig= merge(commonConfig, require('./webpack.mix.production.js'));
}

// 以下代码置于文末
mix.webpackConfig(commonConfig);
// console.log(JSON.stringify(config,null,2));
