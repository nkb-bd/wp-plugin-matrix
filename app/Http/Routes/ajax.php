<?php

/**
 * AJAX Routes
 *
 * This file defines all AJAX routes for the plugin.
 */

use WpBoilerplate\Http\Controllers\AjaxController;
use WpBoilerplate\Http\Controllers\SettingsController;

/**
 * @var \WpBoilerplate\Http\Router $router
 */

// Test endpoint
$router->ajaxGet('test', [AjaxController::class, 'getTest']);

// Settings endpoints
$router->ajaxPost('settings/save', [SettingsController::class, 'save']);
$router->ajaxGet('settings/get', [SettingsController::class, 'get']);

// Add more AJAX routes here
