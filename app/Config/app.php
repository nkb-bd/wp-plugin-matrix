<?php

/**
 * Application configuration
 */
return [
    // Plugin information
    'name' => 'WP Boilerplate',
    'version' => WP_BOILERPLATE_VERSION,
    'description' => 'A WordPress plugin boilerplate',
    
    // Plugin settings
    'settings' => [
        'cache_enabled' => true,
        'cache_expiration' => 3600, // 1 hour
    ],
    
    // Plugin paths
    'paths' => [
        'views' => WP_BOILERPLATE_DIR . 'app/Views',
        'assets' => WP_BOILERPLATE_URL . 'dist',
    ],
    
    // Plugin features
    'features' => [
        'rest_api' => true,
        'shortcodes' => true,
    ],
];
