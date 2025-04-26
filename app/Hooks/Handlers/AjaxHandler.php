<?php

namespace WPPluginMatrixBoilerPlate\Hooks\Handlers;

use WPPluginMatrixBoilerPlate\Core\Router;

/**
 * AjaxHandler class
 *
 * Handles AJAX-related hooks
 */
class AjaxHandler
{
    /**
     * Router instance
     *
     * @var Router
     */
    protected $router;

    /**
     * Constructor
     */
    public function __construct()
    {
        // Load routes
        $this->router = require WP_PLUGIN_MATRIX_BOILER_PLATE_DIR . 'app/Http/routes.php';
    }

    /**
     * Register hooks
     *
     * @return void
     */
    public function register()
    {
        // Handle AJAX requests for logged-in users
        add_action('wp_ajax_wp_plugin_matrix_boiler_plate_admin_ajax', [$this, 'handleAjaxRequests']);

        // Handle AJAX requests for non-logged-in users
        add_action('wp_ajax_nopriv_wp_plugin_matrix_boiler_plate_admin_ajax', [$this, 'handleAjaxRequests']);
    }

    /**
     * Handle AJAX requests
     *
     * @return void
     */
    public function handleAjaxRequests()
    {
        try {
            // Always verify nonce for security
            if (!isset($_REQUEST['_wpnonce'])) {
                wp_send_json_error(['message' => 'Security nonce is missing. Please refresh the page and try again.'], 403);
                return;
            }

            if (!wp_verify_nonce($_REQUEST['_wpnonce'], 'wp_plugin_matrix_boiler_plate_nonce')) {
                wp_send_json_error(['message' => 'Security nonce is invalid. Please refresh the page and try again.'], 403);
                return;
            }

            if (!isset($_REQUEST['route'])) {
                wp_send_json_error(['message' => 'No route specified'], 400);
                return;
            }

            $route = sanitize_text_field($_REQUEST['route']);
            $method = $_SERVER['REQUEST_METHOD'];

            // Log the AJAX request if debugging is enabled
            if (defined('WP_DEBUG') && WP_DEBUG) {
                error_log(sprintf('AJAX request: %s %s', $method, $route));
            }

            do_action('wp_plugin_matrix_boiler_plate/doing_ajax_for_' . $route);

            $routes = $this->router->getAjaxRoutes();

            if (!isset($routes[$route])) {
                wp_send_json_error(['message' => 'Route not found: ' . $route], 404);
                return;
            }

            $routeData = $routes[$route];

            // Check if the request method matches
            if ($routeData['method'] !== $method) {
                wp_send_json_error([
                    'message' => "Method not allowed. Expected {$routeData['method']}, got {$method}"
                ], 405);
                return;
            }

            $callback = $routeData['callback'];

            if (is_array($callback) && count($callback) === 2) {
                list($class, $method) = $callback;

                if (!class_exists($class)) {
                    wp_send_json_error(['message' => 'Controller not found: ' . $class], 500);
                    return;
                }

                $instance = new $class();

                if (!method_exists($instance, $method)) {
                    wp_send_json_error(['message' => 'Method not found: ' . $method], 500);
                    return;
                }

                $response = $instance->$method();

                // If the controller method doesn't send a JSON response, wrap it
                if (!$this->isJsonSent()) {
                    wp_send_json_success($response);
                }

                return $response;
            } elseif (is_callable($callback)) {
                $response = call_user_func($callback);

                // If the callback doesn't send a JSON response, wrap it
                if (!$this->isJsonSent()) {
                    wp_send_json_success($response);
                }

                return $response;
            } else {
                wp_send_json_error(['message' => 'Invalid callback configuration'], 500);
            }
        } catch (\Exception $e) {
            // Log the error
            error_log('AJAX Error: ' . $e->getMessage());

            // Send error response
            wp_send_json_error([
                'message' => 'An error occurred while processing your request',
                'error' => $e->getMessage(),
                'code' => $e->getCode()
            ], 500);
        }
    }

    /**
     * Check if JSON response has already been sent
     *
     * @return bool
     */
    private function isJsonSent()
    {
        return defined('WP_DOING_AJAX') && WP_DOING_AJAX && headers_sent();
    }
}
