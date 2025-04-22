<?php

namespace WpBoilerplate\Hooks\Handlers;

use WpBoilerplate\Admin\AdminMenu;

/**
 * AdminHandler class
 *
 * Handles admin-related hooks
 */
class AdminHandler
{
    /**
     * Admin menu instance
     *
     * @var AdminMenu
     */
    protected $adminMenu;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->adminMenu = new AdminMenu();
    }

    /**
     * Register hooks
     *
     * @return void
     */
    public function register()
    {
        // Register admin menu
        add_action('admin_menu', [$this->adminMenu, 'registerMenuPages']);

        // Disable update nag on plugin pages
        add_action('admin_init', [$this, 'disableUpdateNag'], 20);

        // Enqueue admin assets
        add_action('admin_enqueue_scripts', [$this, 'enqueueAdminAssets']);
    }

    /**
     * Disable update nag on plugin pages
     *
     * @return void
     */
    public function disableUpdateNag()
    {
        $disablePages = [
            'wp-boilerplate',
        ];

        if (isset($_GET['page']) && in_array($_GET['page'], $disablePages)) {
            remove_all_actions('admin_notices');
        }
    }

    /**
     * Enqueue admin assets
     *
     * @param string $hook Current admin page
     * @return void
     */
    public function enqueueAdminAssets($hook)
    {
        // Only load on plugin pages
        if (strpos($hook, 'wp-boilerplate') === false) {
            return;
        }

        wp_enqueue_script(
            'wp-boilerplate-admin',
            wp_boilerplate_asset('js/main.js'),
            ['jquery'],
            WP_BOILERPLATE_VERSION,
            true
        );

        wp_enqueue_style(
            'wp-boilerplate-admin-css',
            wp_boilerplate_asset('js/main.css'),
            [],
            WP_BOILERPLATE_VERSION
        );

        wp_enqueue_style(
            'wp-boilerplate-style',
            wp_boilerplate_asset('css/style.css'),
            [],
            WP_BOILERPLATE_VERSION
        );

        // Localize the script with data
        $translatable = apply_filters('wp_boilerplate/frontend_translatable_strings', [
            'hello' => __('Hello', 'wp-boilerplate'),
            'settings' => __('Settings', 'wp-boilerplate'),
            'dashboard' => __('Dashboard', 'wp-boilerplate'),
            'contact' => __('Contact', 'wp-boilerplate'),
            'save' => __('Save', 'wp-boilerplate'),
            'cancel' => __('Cancel', 'wp-boilerplate'),
            'success' => __('Success', 'wp-boilerplate'),
            'error' => __('Error', 'wp-boilerplate'),
            'loading' => __('Loading...', 'wp-boilerplate'),
        ]);

        wp_localize_script('wp-boilerplate-admin', 'wpBoilerplateAdmin', [
            'ajaxurl' => admin_url('admin-ajax.php'),
            'rest_url' => rest_url('wp-boilerplate/v1'),
            'nonce' => wp_create_nonce('wp_boilerplate_nonce'),
            'assets_url' => wp_boilerplate_asset(''),
            'version' => WP_BOILERPLATE_VERSION,
            'i18n' => $translatable,
            'is_dev' => defined('WP_DEBUG') && WP_DEBUG
        ]);
    }
}
