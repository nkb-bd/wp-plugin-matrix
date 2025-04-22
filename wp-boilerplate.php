<?php
/**
 * Plugin Name: WP Boilerplate
 * Plugin URI: https://github.com/yourusername/wp-boilerplate
 * Description: A simple WordPress plugin boilerplate
 * Version: 1.0.0
 * Author: Your Name
 * Author URI: https://yourwebsite.com
 * License: GPL-2.0-or-later
 * Text Domain: wp-boilerplate
 * Domain Path: /languages
 * Requires PHP: 7.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('WP_BOILERPLATE_VERSION', '1.0.0');
define('WP_BOILERPLATE_FILE', __FILE__);
define('WP_BOILERPLATE_DIR', plugin_dir_path(__FILE__));
define('WP_BOILERPLATE_URL', plugin_dir_url(__FILE__));
define('WP_BOILERPLATE_BASENAME', plugin_basename(__FILE__));

// Load Composer autoloader if it exists
if (file_exists(WP_BOILERPLATE_DIR . 'vendor/autoload.php')) {
    require_once WP_BOILERPLATE_DIR . 'vendor/autoload.php';
}

// Load helper functions
require_once WP_BOILERPLATE_DIR . 'app/Helpers/functions.php';

/**
 * Main Plugin Class
 */
class WpBoilerplatePlugin {
    /**
     * Plugin instance
     *
     * @var WpBoilerplatePlugin
     */
    private static $instance = null;

    /**
     * Get plugin instance
     *
     * @return WpBoilerplatePlugin
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
        register_activation_hook(WP_BOILERPLATE_FILE, array($this, 'activate'));
        register_deactivation_hook(WP_BOILERPLATE_FILE, array($this, 'deactivate'));

        // Load translations
        add_action('plugins_loaded', array($this, 'loadTextdomain'));

        // Initialize the modern structure
        if (class_exists('\WpBoilerplate\Hooks\HookManager')) {
            $hookManager = new \WpBoilerplate\Hooks\HookManager();
            $hookManager->registerHooks();
        }
    }

    /**
     * Plugin activation
     */
    public function activate() {
        // Create database tables if needed
        // flush_rewrite_rules();
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
            'wp-boilerplate',
            false,
            dirname(WP_BOILERPLATE_BASENAME) . '/languages/'
        );
    }
}

/**
 * Initialize the plugin
 *
 * @return WpBoilerplatePlugin Plugin instance
 */
function wp_boilerplate() {
    return WpBoilerplatePlugin::getInstance();
}

// Start the plugin
add_action('plugins_loaded', 'wp_boilerplate');
