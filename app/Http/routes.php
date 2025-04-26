<?php

/**
 * Routes Configuration
 *
 * This file loads all route files for the plugin.
 */

use WPPluginMatrixBoilerPlate\Core\Router;

// Create router instance
$router = new Router('wp-plugin-matrix-boiler-plate/v1');

// Load route files
require_once __DIR__ . '/Routes/api.php';
require_once __DIR__ . '/Routes/ajax.php';

// Return router instance
return $router;
