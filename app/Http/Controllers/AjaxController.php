<?php

namespace WpBoilerplate\Http\Controllers;

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

        return wp_send_json([
            'success' => true,
            'data' => "Response from Server: AjaxController",
            'time' => current_time('mysql'),
            'request' => $_REQUEST
        ]);
    }
}
