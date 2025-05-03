<?php


/**
 * Simple PSR-4 Autoloader for WP Plugin Matrix BoilerPlate
 *
 * This autoloader follows PSR-4 standards to load classes based on their namespace.
 * It replaces the need for Composer's autoloader for this plugin.
 */

/**
 * WP Plugin Matrix BoilerPlate Autoloader
 *
 * @param string $class The fully-qualified class name
 * @return void
 */
function wp_plugin_matrix_boiler_plate_autoloader($class) {
    // Plugin namespace prefix
    $prefix = 'WPPluginMatrixBoilerPlate\\';

    // Base directory for the namespace prefix
    $base_dir = WP_PLUGIN_MATRIX_BOILER_PLATE_DIR . 'app/';

    // Check if the class uses the namespace prefix
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        // No, move to the next registered autoloader
        return;
    }

    // Get the relative class name
    $relative_class = substr($class, $len);

    // Replace namespace separators with directory separators
    // and append .php
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    // If the file exists, require it
    if (file_exists($file)) {
        require_once $file;
    }
}

// Register the autoloader
spl_autoload_register('wp_plugin_matrix_boiler_plate_autoloader');

// Load helper functions file
require_once WP_PLUGIN_MATRIX_BOILER_PLATE_DIR . 'app/Helpers/functions.php';
