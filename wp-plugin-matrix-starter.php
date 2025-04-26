<?php
/**a
 * Plugin Name: WP Plugin Matrix - Starter
 * Plugin URI: https://github.com/yourusername/wp-plugin-matrix-boiler-plate
 * Description: WP Plugin Matrix - Starter - A WordPress plugin
 * Version: 1.0.0
 * Author: Lukman Nakib
 * Author URI: https://yourwebsite.com
 * License: GPL-2.0-or-later
 * Text Domain: wp_plugin_matrix_starter
 * Domain Path: /languages
 * Requires PHP: 7.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants with unique prefix to avoid conflicts
define('WP_PLUGIN_MATRIX_BOILER_PLATE_VERSION', '1.0.0');
define('WP_PLUGIN_MATRIX_BOILER_PLATE_FILE', __FILE__);
define('WP_PLUGIN_MATRIX_BOILER_PLATE_DIR', plugin_dir_path(__FILE__));
define('WP_PLUGIN_MATRIX_BOILER_PLATE_URL', plugin_dir_url(__FILE__));
define('WP_PLUGIN_MATRIX_BOILER_PLATE_BASENAME', plugin_basename(__FILE__));
define('WP_PLUGIN_MATRIX_BOILER_PLATE_ENV', defined('WP_DEBUG') && WP_DEBUG ? 'development' : 'production');

// Load custom autoloader
require_once WP_PLUGIN_MATRIX_BOILER_PLATE_DIR . 'app/autoloader.php';

// Load bootstrap file
require_once WP_PLUGIN_MATRIX_BOILER_PLATE_DIR . 'app/bootstrap.php';

/**
 * Main Plugin Class
 */
class WPPluginMatrixBoilerPlatePlugin {
    /**
     * Plugin instance
     *
     * @var WPPluginMatrixBoilerPlatePlugin
     */
    private static $instance = null;

    /**
     * Get plugin instance
     *
     * @return WPPluginMatrixBoilerPlatePlugin
     */
    public static function getInstance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct() {
        // Register activation/deactivation hooks
        register_activation_hook(WP_PLUGIN_MATRIX_BOILER_PLATE_FILE, array($this, 'activate'));
        register_deactivation_hook(WP_PLUGIN_MATRIX_BOILER_PLATE_FILE, array($this, 'deactivate'));

        // Load translations
        wp_plugin_matrix_boiler_plate_add_action('plugins_loaded', array($this, 'loadTextdomain'));

        // The App class now handles all initialization
        // No need to manually initialize hooks here
    }

    /**
     * Plugin activation
     */
    public function activate() {
        // Run database migrations
        $activator = new \WPPluginMatrixBoilerPlate\Services\Activator();
        $activator->migrateDatabases(is_multisite());

        // Flush rewrite rules
        flush_rewrite_rules();
    }

    /**
     * Plugin deactivation
     */
    public function deactivate() {
        // Cleanup tasks on deactivation
        // flush_rewrite_rules();
    }

    /**
     * Load plugin textdomain
     */
    public function loadTextdomain() {
        load_plugin_textdomain(
            'wp-plugin-matrix-boiler-plate',
            false,
            dirname(WP_PLUGIN_MATRIX_BOILER_PLATE_BASENAME) . '/languages/'
        );
    }
}

/**
 * Initialize the plugin
 *
 * @return WPPluginMatrixBoilerPlatePlugin Plugin instance
 */
function wp_plugin_matrix_boiler_plate() {
    return WPPluginMatrixBoilerPlatePlugin::getInstance();
}

// Start the plugin
add_action('plugins_loaded', 'wp_plugin_matrix_boiler_plate');
