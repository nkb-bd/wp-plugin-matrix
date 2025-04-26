<?php

namespace WPPluginMatrixBoilerPlate\Core;

use WPPluginMatrixBoilerPlate\Hooks\HookManager;

/**
 * App class
 *
 * Main application entry point that handles initialization,
 * service registration, and hook registration in a centralized way.
 */
class App
{
    /**
     * Singleton instance
     *
     * @var App
     */
    protected static $instance = null;

    /**
     * Services container
     *
     * @var array
     */
    protected $services = [];

    /**
     * Get singleton instance
     *
     * @return App
     */
    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Private constructor to enforce singleton pattern
     */
    private function __construct()
    {
        // Initialize debugging
        Debug::init();
        Debug::addToSequence('App::__construct', microtime(true));
    }

    /**
     * Bootstrap the application
     *
     * @return void
     */
    public function bootstrap()
    {
        Debug::addToSequence('App::bootstrap', microtime(true));

        // Define environment
        $this->defineEnvironment();

        // Register services
        $this->registerServices();

        // Helper functions are now loaded by the autoloader

        // Register hooks
        $this->registerHooks();

        // Initialize plugin
        $this->initialize();

        Debug::addToSequence('App::bootstrap completed', microtime(true));
    }

    /**
     * Define environment settings
     *
     * @return void
     */
    protected function defineEnvironment()
    {
        Debug::addToSequence('App::defineEnvironment', microtime(true));
        // WP_PLUGIN_MATRIX_BOILER_PLATE_ENV is now defined in the main plugin file

        if (WP_PLUGIN_MATRIX_BOILER_PLATE_ENV === 'development') {
            error_reporting(E_ALL);
            ini_set('display_errors', 1);
        }
    }

    /**
     * Register all services
     *
     * @return void
     */
    protected function registerServices()
    {
        Debug::addToSequence('App::registerServices', microtime(true));

        // Register core services
        $this->registerService('cache', new Cache());
        $this->registerService('security', new Security());
        $this->registerService('view', new View());
        $this->registerService('asset', new Asset());
        $this->registerService('config', new Config());
        $this->registerService('logger', new Logger());

        // Register application services
        // Add your custom services here
    }

    /**
     * Register a service
     *
     * @param string $name Service name
     * @param object $instance Service instance
     * @return void
     */
    public function registerService($name, $instance)
    {
        // Store in local container
        $this->services[$name] = $instance;

        // Register with Facade system
        Facade::registerService($name, $instance);

        // Log for debugging
        Debug::registerService($name, $instance);
    }

    /**
     * Get a service
     *
     * @param string $name Service name
     * @return mixed|null
     */
    public function getService($name)
    {
        return isset($this->services[$name]) ? $this->services[$name] : null;
    }

    /**
     * Get all registered services
     *
     * @return array
     */
    public function getServices()
    {
        return $this->services;
    }

    /**
     * Load helper functions
     *
     * @return void
     */
    protected function loadHelpers()
    {
        Debug::addToSequence('App::loadHelpers', microtime(true));

        require_once WP_PLUGIN_MATRIX_BOILER_PLATE_DIR . 'app/Helpers/functions.php';
    }

    /**
     * Register all hooks
     *
     * @return void
     */
    protected function registerHooks()
    {
        Debug::addToSequence('App::registerHooks', microtime(true));

        $hookManager = new HookManager();
        $hookManager->registerHooks();
    }

    /**
     * Initialize the plugin
     *
     * @return void
     */
    protected function initialize()
    {
        Debug::addToSequence('App::initialize', microtime(true));

        // Fire initialization action
        do_action('wp_plugin_matrix_boiler_plate_init');

        // Add debug panel in admin and frontend footers if WP_DEBUG is enabled
        if (defined('WP_DEBUG') && WP_DEBUG) {
            // Use our hook tracking function
            wp_plugin_matrix_boiler_plate_add_action('admin_footer', [$this, 'renderDebugPanel']);
            wp_plugin_matrix_boiler_plate_add_action('wp_footer', [$this, 'renderDebugPanel']);

            // Also add it to the login page footer
            wp_plugin_matrix_boiler_plate_add_action('login_footer', [$this, 'renderDebugPanel']);
        }
    }

    /**
     * Render debug panel in footer
     *
     * This method is hooked to both admin_footer and wp_footer,
     * but we use a static flag to ensure it only runs once.
     *
     * @return void
     */
    public function renderDebugPanel()
    {
        static $panel_rendered = false;

        // Only render the panel once
        if ($panel_rendered) {
            return;
        }

        Debug::addToSequence('App::renderDebugPanel', microtime(true));

        // Check if debug panel is disabled via cookie
        $debug_panel_hidden = isset($_COOKIE['wp_plugin_matrix_boiler_plate_debug_hidden']) && $_COOKIE['wp_plugin_matrix_boiler_plate_debug_hidden'] === 'true';

        // Add toggle button
        echo '<div style="position: fixed; bottom: 10px; right: 10px; z-index: 99999;">';
        echo '<button id="wp-plugin-matrix-boiler-plate-debug-toggle" style="background-color: #0073aa; color: white; border: none; padding: 5px 10px; border-radius: 3px; cursor: pointer;">';
        echo $debug_panel_hidden ? 'Show Debug Panel' : 'Hide Debug Panel';
        echo '</button>';
        echo '</div>';

        // Add a wrapper with some basic styling
        echo '<div id="wp-plugin-matrix-boiler-plate-debug-panel" style="margin: 20px auto; padding: 20px; border: 1px solid #ccc; background-color: #f8f8f8; font-family: monospace; font-size: 12px; line-height: 1.5; color: #333; max-width: 80%; overflow: auto; ' . ($debug_panel_hidden ? 'display: none;' : '') . '">';
        echo '<h2 style="margin-top: 0;">WP Plugin Matrix BoilerPlate Debug Panel</h2>';

        // Dump the debug info
        Debug::dump();

        echo '</div>';

        // Add JavaScript to toggle the panel
        echo '<script>
            document.getElementById("wp-plugin-matrix-boiler-plate-debug-toggle").addEventListener("click", function() {
                var panel = document.getElementById("wp-plugin-matrix-boiler-plate-debug-panel");
                var isHidden = panel.style.display === "none";
                panel.style.display = isHidden ? "block" : "none";
                this.textContent = isHidden ? "Hide Debug Panel" : "Show Debug Panel";

                // Set cookie to remember state
                document.cookie = "wp_plugin_matrix_boiler_plate_debug_hidden=" + (!isHidden) + "; path=/; max-age=86400";
            });
        </script>';

        // Mark as rendered
        $panel_rendered = true;
    }
}
