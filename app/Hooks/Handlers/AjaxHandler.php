<?php

namespace WpBoilerplate\Hooks\Handlers;

use WpBoilerplate\Core\Router;

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
        $this->router = require WP_BOILERPLATE_DIR . 'app/Http/routes.php';
    }

    /**
     * Register hooks
     *
     * @return void
     */
    public function register()
    {
        // Handle AJAX requests for logged-in users
        add_action('wp_ajax_wp_boilerplate_admin_ajax', [$this, 'handleAjaxRequests']);

        // Handle AJAX requests for non-logged-in users
        add_action('wp_ajax_nopriv_wp_boilerplate_admin_ajax', [$this, 'handleAjaxRequests']);
    }

    /**
     * Handle AJAX requests
     *
     * @return void
     */
    public function handleAjaxRequests()
    {
        if (!isset($_REQUEST['route'])) {
            wp_send_json_error(['message' => 'No route specified'], 400);
            return;
        }

        $route = sanitize_text_field($_REQUEST['route']);
        $method = $_SERVER['REQUEST_METHOD'];

        do_action('wp_boilerplate/doing_ajax_for_' . $route);

        $routes = $this->router->getAjaxRoutes();

        if (!isset($routes[$route])) {
            wp_send_json_error(['message' => 'Route not found'], 404);
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
                wp_send_json_error(['message' => 'Controller not found'], 500);
                return;
            }

            $instance = new $class();

            if (!method_exists($instance, $method)) {
                wp_send_json_error(['message' => 'Method not found'], 500);
                return;
            }

            return $instance->$method();
        } elseif (is_callable($callback)) {
            return call_user_func($callback);
        } else {
            wp_send_json_error(['message' => 'Invalid callback'], 500);
        }
    }
}
