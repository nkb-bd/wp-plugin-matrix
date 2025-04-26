<?php

namespace WPPluginMatrixBoilerPlate\Http\Controllers;

/**
 * Class AjaxController
 *
 * Handles AJAX requests
 */
class AjaxController
{
    /**
     * Test endpoint
     *
     * @return void
     */
    public function getTest()
    {
        // Log the request for debugging
        error_log('AjaxController::getTest() called');

        // Get some useful WordPress info for the test response
        $wp_info = [
            'version' => get_bloginfo('version'),
            'name' => get_bloginfo('name'),
            'url' => get_bloginfo('url'),
            'admin_email' => get_bloginfo('admin_email'),
            'language' => get_bloginfo('language'),
            'timezone' => wp_timezone_string(),
        ];

        return wp_send_json([
            'success' => true,
            'data' => "Response from Server: AjaxController",
            'time' => current_time('mysql'),
            'wp_info' => $wp_info,
            'request' => array_map(function($value) {
                // Sanitize sensitive data
                return is_string($value) && strpos($value, 'nonce') !== false ? '[REDACTED]' : $value;
            }, $_REQUEST)
        ]);
    }
}
