const mix = require('laravel-mix');
const path = require('path');
const fs = require('fs-extra');
const tailwindcss = require('tailwindcss');

// Set the public directory for compiled assets
mix.setPublicPath('./dist');

// Development mode configuration
if (!mix.inProduction()) {
    // mix
    //     .webpackConfig({ devtool: 'source-map' });
    //     .sourceMaps(false);
} else {
    // Clean up the dist directory for a fresh production build
    fs.removeSync('dist');
}

// Common Webpack configuration
mix.webpackConfig({
    resolve: {
        alias: {
            '@': path.resolve('resources/js/'), // Alias for easier imports
        }
    },
    stats: {
        assets: true
    }
});

// Asset compilation tasks
mix
    .js('resources/js/admin/main.js', 'js/main.js')
    .vue({
        version: 3,
        extractStyles: true // Extract Vue component styles to a CSS file
    })
    .sass('resources/scss/style.scss', 'css/style.css')
    .options({
        processCssUrls: false,
        postCss: [
            tailwindcss('./tailwind.config.js'),
            require('autoprefixer')
        ],
    });

// Copy images
mix.copyDirectory('resources/images', 'dist/images');

// Version assets in production
if (mix.inProduction()) {
    mix.version();
}
