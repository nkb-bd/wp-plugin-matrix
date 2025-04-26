<?php

namespace WPPluginMatrixBoilerPlate\Core;

/**
 * Router class
 *
 * Handles registration of REST API and AJAX routes
 */
class Router
{
    /**
     * REST API namespace
     *
     * @var string
     */
    private $namespace = '';

    /**
     * AJAX routes
     *
     * @var array
     */
    private $ajaxRoutes = [];

    /**
     * Constructor
     *
     * @param string $namespace REST API namespace
     */
    public function __construct($namespace = 'wp-plugin-matrix-boiler-plate/v1')
    {
        $this->namespace = $namespace;
    }

    /**
     * REST API routes
     *
     * @var array
     */
    private $restRoutes = [];

    /**
     * Register a REST API route
     *
     * @param string $method HTTP method
     * @param string $endpoint API endpoint
     * @param callable $callback Callback function
     * @param array|callable $permissions Permission callback or array of capabilities
     * @return Router
     */
    public function route($method, $endpoint, $callback, $permissions = [])
    {
        $endpoint = str_replace('{id}', '(?P<id>[\d]+)', $endpoint);

        // Store the route for later registration
        $this->restRoutes[] = [
            'endpoint' => $endpoint,
            'args' => [
                'methods'  => $method,
                'callback' => function($request) use ($callback) {
                    $result = call_user_func($callback, $request);

                    if (is_wp_error($result)) {
                        return new \WP_REST_Response([
                            'code' => $result->get_error_code(),
                            'message' => $result->get_error_message(),
                            'data' => $result->get_error_data()
                        ], 422);
                    }

                    if (ob_get_length()) {
                        ob_clean();
                    }

                    return rest_ensure_response($result);
                },
                'permission_callback' => function($request) use ($permissions) {
                    if (is_array($permissions)) {
                        if (count($permissions)) {
                            foreach ($permissions as $permission) {
                                if (current_user_can($permission)) {
                                    return true;
                                }
                            }
                            return false;
                        }
                        return true;
                    }

                    return call_user_func($permissions, $request);
                }
            ]
        ];

        return $this;
    }

    /**
     * Register a GET route
     *
     * @param string $endpoint API endpoint
     * @param callable $callback Callback function
     * @param array|callable $permissions Permission callback or array of capabilities
     * @return Router
     */
    public function get($endpoint, $callback, $permissions = [])
    {
        $this->route(\WP_REST_Server::READABLE, $endpoint, $callback, $permissions);
        return $this;
    }

    /**
     * Register a POST route
     *
     * @param string $endpoint API endpoint
     * @param callable $callback Callback function
     * @param array|callable $permissions Permission callback or array of capabilities
     * @return Router
     */
    public function post($endpoint, $callback, $permissions = [])
    {
        $this->route(\WP_REST_Server::CREATABLE, $endpoint, $callback, $permissions);
        return $this;
    }

    /**
     * Register a PUT route
     *
     * @param string $endpoint API endpoint
     * @param callable $callback Callback function
     * @param array|callable $permissions Permission callback or array of capabilities
     * @return Router
     */
    public function put($endpoint, $callback, $permissions = [])
    {
        $this->route(\WP_REST_Server::EDITABLE, $endpoint, $callback, $permissions);
        return $this;
    }

    /**
     * Register a DELETE route
     *
     * @param string $endpoint API endpoint
     * @param callable $callback Callback function
     * @param array|callable $permissions Permission callback or array of capabilities
     * @return Router
     */
    public function delete($endpoint, $callback, $permissions = [])
    {
        $this->route(\WP_REST_Server::DELETABLE, $endpoint, $callback, $permissions);
        return $this;
    }

    /**
     * Register an AJAX route
     *
     * @param string $method HTTP method (GET, POST, etc.)
     * @param string $action AJAX action
     * @param callable $callback Callback function
     * @return Router
     */
    public function ajax($method, $action, $callback)
    {
        $this->ajaxRoutes[$action] = [
            'method' => strtoupper($method),
            'callback' => $callback
        ];
        return $this;
    }

    /**
     * Register a GET AJAX route
     *
     * @param string $action AJAX action
     * @param callable $callback Callback function
     * @return Router
     */
    public function ajaxGet($action, $callback)
    {
        return $this->ajax('GET', $action, $callback);
    }

    /**
     * Register a POST AJAX route
     *
     * @param string $action AJAX action
     * @param callable $callback Callback function
     * @return Router
     */
    public function ajaxPost($action, $callback)
    {
        return $this->ajax('POST', $action, $callback);
    }

    /**
     * Get all AJAX routes
     *
     * @return array
     */
    public function getAjaxRoutes()
    {
        return $this->ajaxRoutes;
    }

    /**
     * Register all REST API routes
     *
     * This method should only be called during the rest_api_init action
     *
     * @return void
     */
    public function registerRestRoutes()
    {
        // Register all stored routes
        foreach ($this->restRoutes as $route) {
            register_rest_route($this->namespace, $route['endpoint'], $route['args']);
        }
    }
}
