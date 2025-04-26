<?php

namespace WPPluginMatrixBoilerPlate\Hooks\Handlers;

use WPPluginMatrixBoilerPlate\Facades\Asset;
use WPPluginMatrixBoilerPlate\Facades\Security;

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
            'wp-plugin-matrix-boiler-plate-admin',
            'js/main.js',
            ['jquery', 'wp-element'], // wp-element for React compatibility
            WP_PLUGIN_MATRIX_BOILER_PLATE_VERSION,
            true,
            'admin'
        );

        // Admin CSS
        Asset::registerStyle(
            'wp-plugin-matrix-boiler-plate-admin-css',
            'css/style.css', // Changed from js/main.css to css/style.css
            [],
            WP_PLUGIN_MATRIX_BOILER_PLATE_VERSION,
            'all',
            'admin'
        );

        // Localize admin script with data and translations
        $translatable = apply_filters('wp_plugin_matrix_boiler_plate/admin_translatable_strings', [
            'hello' => __('Hello', 'wp-plugin-matrix-boiler-plate'),
            'settings' => __('Settings', 'wp-plugin-matrix-boiler-plate'),
            'dashboard' => __('Dashboard', 'wp-plugin-matrix-boiler-plate'),
            'save' => __('Save', 'wp-plugin-matrix-boiler-plate'),
            'cancel' => __('Cancel', 'wp-plugin-matrix-boiler-plate'),
            'success' => __('Success', 'wp-plugin-matrix-boiler-plate'),
            'error' => __('Error', 'wp-plugin-matrix-boiler-plate'),
            'loading' => __('Loading...', 'wp-plugin-matrix-boiler-plate'),
        ]);

        Asset::localizeScript(
            'wp-plugin-matrix-boiler-plate-admin',
            'wpPluginMatrixBoilerPlateAdmin',
            [
                'ajaxurl' => admin_url('admin-ajax.php'),
                'nonce' => Security::createNonce('wp_plugin_matrix_boiler_plate_nonce'),
                'is_dev' => defined('WP_DEBUG') && WP_DEBUG,
                'plugin_url' => WP_PLUGIN_MATRIX_BOILER_PLATE_URL,
                'assets_url' => wp_plugin_matrix_boiler_plate_asset(''),
                'rest_url' => rest_url('wp-plugin-matrix-boiler-plate/v1'),
                'version' => WP_PLUGIN_MATRIX_BOILER_PLATE_VERSION,
                'i18n' => $translatable,
            ]
        );

        // ===== FRONTEND ASSETS =====

        // Only register frontend assets if needed
        if (apply_filters('wp_plugin_matrix_boiler_plate/load_frontend_assets', true)) {

        }
    }
}
