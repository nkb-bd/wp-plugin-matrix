<?php

namespace WPPluginMatrixBoilerPlate\Hooks\Handlers;

use WPPluginMatrixBoilerPlate\Admin\AdminMenu;

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

        // Note: Asset enqueuing is now handled by the AssetHandler
    }

    /**
     * Disable update nag on plugin pages
     *
     * @return void
     */
    public function disableUpdateNag()
    {
        $disablePages = [
            'wp-plugin-matrix-boiler-plate',
            'wp-plugin-matrix-boiler-plate-demo',
        ];

        if (isset($_GET['page']) && in_array($_GET['page'], $disablePages)) {
            remove_all_actions('admin_notices');
        }
    }
}
