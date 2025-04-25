<?php

namespace WpBoilerplate\Hooks\Handlers;

use WpBoilerplate\Facades\Asset;
use WpBoilerplate\Facades\Security;

/**
 * Asset Handler
 *
 * Registers and enqueues scripts and styles for the plugin
 */
class AssetHandler
{
    /**
     * Register hooks
     *
     * This method is called during the 'plugins_loaded' action.
     *
     * @return void
     */
    public function register()
    {
        // Register assets on init to ensure WordPress is fully loaded
        add_action('init', [$this, 'registerAssets'], 10);
    }

    /**
     * Register all assets
     *
     * This method registers all scripts and styles used by the plugin.
     * Simplified to follow WordPress standards and support Vue.js in admin.
     *
     * @return void
     */
    public function registerAssets()
    {
        // ===== ADMIN ASSETS =====

        // Main admin script (Vue.js app)
        Asset::registerScript(
            'wp-boilerplate-admin',
            'js/main.js',
            ['jquery', 'wp-element'], // wp-element for React compatibility
            WP_BOILERPLATE_VERSION,
            true,
            'admin'
        );

        // Admin CSS
        Asset::registerStyle(
            'wp-boilerplate-admin-css',
            'css/style.css', // Changed from js/main.css to css/style.css
            [],
            WP_BOILERPLATE_VERSION,
            'all',
            'admin'
        );

        // Localize admin script with data and translations
        $translatable = apply_filters('wp_boilerplate/admin_translatable_strings', [
            'hello' => __('Hello', 'wp-boilerplate'),
            'settings' => __('Settings', 'wp-boilerplate'),
            'dashboard' => __('Dashboard', 'wp-boilerplate'),
            'save' => __('Save', 'wp-boilerplate'),
            'cancel' => __('Cancel', 'wp-boilerplate'),
            'success' => __('Success', 'wp-boilerplate'),
            'error' => __('Error', 'wp-boilerplate'),
            'loading' => __('Loading...', 'wp-boilerplate'),
        ]);

        Asset::localizeScript(
            'wp-boilerplate-admin',
            'wpBoilerplateAdmin',
            [
                'ajaxurl' => admin_url('admin-ajax.php'),
                'nonce' => Security::createNonce('wp_boilerplate_nonce'),
                'is_dev' => defined('WP_DEBUG') && WP_DEBUG,
                'plugin_url' => WP_BOILERPLATE_URL,
                'assets_url' => wp_boilerplate_asset(''),
                'rest_url' => rest_url('wp-boilerplate/v1'),
                'version' => WP_BOILERPLATE_VERSION,
                'i18n' => $translatable,
            ]
        );

        // ===== FRONTEND ASSETS =====

        // Only register frontend assets if needed
        if (apply_filters('wp_boilerplate/load_frontend_assets', true)) {

        }
    }
}
