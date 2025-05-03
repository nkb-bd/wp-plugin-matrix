<?php


/**
 * Bootstrap the application
 *
 * This file is responsible for bootstrapping the application
 * and registering all services with the facade registry.
 *
 * This file is loaded directly from the main plugin file (wp-plugin-matrix-boiler-plate.php)
 * before any WordPress hooks are executed, ensuring that all services and
 * functionality are available throughout the plugin's lifecycle.
 */

use WPPluginMatrixBoilerPlate\Core\App;

/**
 * Bootstrap the application using the centralized App class
 *
 * This provides a single entry point for all initialization,
 * service registration, and hook registration, making it easier
 * to debug and understand the execution flow.
 */
$app = App::getInstance();
$app->bootstrap();
