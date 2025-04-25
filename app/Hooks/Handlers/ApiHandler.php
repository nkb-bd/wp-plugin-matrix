<?php

namespace WpBoilerplate\Hooks\Handlers;

/**
 * ApiHandler class
 *
 * Handles REST API-related hooks
 */
class ApiHandler
{
    /**
     * Router instance
     *
     * @var \WpBoilerplate\Core\Router
     */
    protected $router;

    /**
     * Constructor
     */
    public function __construct()
    {
        // We'll load the router when needed to avoid loading it too early
    }

    /**
     * Register hooks
     *
     * This method is called by the HookManager during the 'plugins_loaded' action.
     * It registers callbacks for the 'rest_api_init' hook (which runs when the REST API
     * is initialized) and the 'rest_pre_dispatch' filter (which runs before each REST
     * request is dispatched to its handler).
     *
     * @return void
     */
    public function register()
    {
        // Register REST API routes
        wp_boilerplate_add_action('rest_api_init', [$this, 'registerRestRoutes']);

        // Add custom REST API error handling
        wp_boilerplate_add_filter('rest_pre_dispatch', [$this, 'handlePreDispatch'], 10, 3);
    }

    /**
     * Register REST API routes
     *
     * @return void
     */
    public function registerRestRoutes()
    {
        try {
            // Load routes
            $this->router = require WP_BOILERPLATE_DIR . 'app/Http/routes.php';

            // Register REST API routes
            $this->router->registerRestRoutes();

            // Log registration
            wp_boilerplate_info('REST API routes registered successfully');
        } catch (\Exception $e) {
            // Log the error
            wp_boilerplate_error('Error registering REST API routes', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Handle pre-dispatch for REST API requests
     *
     * @param mixed $result Response to replace the requested version with
     * @param \WP_REST_Server $server Server instance
     * @param \WP_REST_Request $request Request used to generate the response
     * @return mixed
     */
    public function handlePreDispatch($result, $server, $request)
    {
        // Only handle our plugin's routes
        if (strpos($request->get_route(), '/wp-boilerplate/') === false) {
            return $result;
        }

        // Log API requests if debugging is enabled
        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log(sprintf('REST API request: %s %s', $request->get_method(), $request->get_route()));
        }

        return $result;
    }
}
