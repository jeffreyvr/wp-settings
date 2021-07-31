let mix = require('laravel-mix');

mix.setPublicPath('./');

mix.sass('resources/css/wp-settings.scss', 'assets');

mix.js('resources/js/wp-settings.js', 'assets');