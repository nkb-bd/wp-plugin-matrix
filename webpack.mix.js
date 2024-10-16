const mix = require('laravel-mix');
const path = require('path');
const fs = require('fs-extra');
const tailwindcss = require('tailwindcss');

// Set the public directory for compiled assets
mix.setPublicPath('./assets');

// Development mode configuration
if (!mix.inProduction()) {
    // mix
    //     .webpackConfig({ devtool: 'source-map' });
    //     .sourceMaps(false);
} else {
    // Clean up the assets directory for a fresh production build
    fs.removeSync('assets');
}

// Common Webpack configuration
mix.webpackConfig({
    resolve: {
        alias: {
            '@': path.resolve('src/'), // Alias for easier imports
        }
    },
    stats: {
        assets: true
    }
});

// Asset compilation tasks
mix
    .js('src/admin/main.js', 'assets/js/main.js')
    .vue({
        version: 3,
        extractStyles: true // Extract Vue component styles to a CSS file
    })
    .sass('src/assets/style.scss', 'assets/css/style.css')
    .options({
        processCssUrls: false,
        postCss: [
            tailwindcss('./tailwind.config.js'),
            require('autoprefixer')
        ],
    });
