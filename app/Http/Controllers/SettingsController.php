<?php

namespace WpBoilerplate\Http\Controllers;

/**
 * Class SettingsController
 * 
 * Handles settings-related requests
 */
class SettingsController
{
    /**
     * Get all settings
     *
     * @return void
     */
    public function get()
    {
        $settings = get_option('wp_boilerplate_settings', []);
        
        return wp_send_json_success([
            'settings' => $settings
        ]);
    }
    
    /**
     * Save settings
     *
     * @return void
     */
    public function save()
    {
        if (!current_user_can('manage_options')) {
            return wp_send_json_error([
                'message' => __('You do not have permission to perform this action.', 'wp-boilerplate')
            ], 403);
        }
        
        $settings = isset($_POST['settings']) ? $_POST['settings'] : [];
        
        // Sanitize settings
        $sanitizedSettings = [];
        
        foreach ($settings as $key => $value) {
            $sanitizedSettings[sanitize_key($key)] = sanitize_text_field($value);
        }
        
        update_option('wp_boilerplate_settings', $sanitizedSettings);
        
        return wp_send_json_success([
            'message' => __('Settings saved successfully.', 'wp-boilerplate'),
            'settings' => $sanitizedSettings
        ]);
    }
    
    /**
     * REST API endpoint for settings
     *
     * @param \WP_REST_Request $request
     * @return \WP_REST_Response
     */
    public function index($request)
    {
        $settings = get_option('wp_boilerplate_settings', []);
        
        return rest_ensure_response([
            'settings' => $settings
        ]);
    }
}
