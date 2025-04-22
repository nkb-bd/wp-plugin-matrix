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
     * Register hooks
     *
     * @return void
     */
    public function register()
    {
        // Register REST API routes
        add_action('rest_api_init', [$this, 'registerRestRoutes']);
    }

    /**
     * Register REST API routes
     *
     * @return void
     */
    public function registerRestRoutes()
    {
        // Load routes
        $router = require WP_BOILERPLATE_DIR . 'app/Http/routes.php';

        // Register REST API routes
        $router->registerRestRoutes();
    }
}
