<?php

/**
 * Application configuration
 */
return [
    // Plugin information
    'name' => 'WP Plugin Matrix BoilerPlate',
    'version' => WP_PLUGIN_MATRIX_BOILER_PLATE_VERSION,
    'description' => 'A WordPress plugin boilerplate',
    
    // Plugin settings
    'settings' => [
        'cache_enabled' => true,
        'cache_expiration' => 3600, // 1 hour
    ],
    
    // Plugin paths
    'paths' => [
        'views' => WP_PLUGIN_MATRIX_BOILER_PLATE_DIR . 'app/Views',
        'assets' => WP_PLUGIN_MATRIX_BOILER_PLATE_URL . 'dist',
    ],
    
    // Plugin features
    'features' => [
        'rest_api' => true,
        'shortcodes' => true,
    ],
];
