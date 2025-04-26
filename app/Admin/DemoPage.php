<?php

namespace WPPluginMatrixBoilerPlate\Admin;

/**
 * Demo Page
 * 
 * Showcases UI components and commands
 */
class DemoPage
{
    /**
     * Register the admin page
     */
    public function register()
    {
        add_action('admin_menu', [$this, 'addAdminPage']);
    }
    
    /**
     * Add admin page
     */
    public function addAdminPage()
    {
        add_submenu_page(
            'wp-plugin-matrix-boiler-plate', // Parent slug
            __('Components Demo', 'wp-plugin-matrix-boiler-plate'),
            __('Components Demo', 'wp-plugin-matrix-boiler-plate'),
            'manage_options',
            'wp-plugin-matrix-boiler-plate-demo',
            [$this, 'renderPage']
        );
    }
    
    /**
     * Render the admin page
     */
    public function renderPage()
    {
        echo '<div id="wp_plugin_matrix_boiler_plate_demo_page"></div>';
    }
}
