<?php

/**
 * REST API Routes
 *
 * This file defines all REST API routes for the plugin.
 */

use WpBoilerplate\Http\Controllers\SettingsController;

/**
 * @var \WpBoilerplate\Http\Router $router
 */

// Settings endpoints
$router->get('settings', [SettingsController::class, 'index'], ['manage_options']);
$router->post('settings', [SettingsController::class, 'update'], ['manage_options']);

// Add more REST API routes here
