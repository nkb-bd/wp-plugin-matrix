<?php

namespace WpBoilerplate\Admin;

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
            'wp-boilerplate', // Parent slug
            __('Components Demo', 'wp-boilerplate'),
            __('Components Demo', 'wp-boilerplate'),
            'manage_options',
            'wp-boilerplate-demo',
            [$this, 'renderPage']
        );
    }
    
    /**
     * Render the admin page
     */
    public function renderPage()
    {
        echo '<div id="wp_boilerplate_demo_page"></div>';
    }
}
