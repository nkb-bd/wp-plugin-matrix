<?php

namespace WpBoilerplate\Admin;

/**
 * Class AdminMenu
 *
 * Handles admin menu registration and admin page rendering
 */
class AdminMenu
{
    /**
     * Admin menu items
     *
     * @var array
     */
    protected $menuItems = [];

    /**
     * Constructor
     */
    public function __construct()
    {
        // Add default menu items
        $this->addMenuItem('dashboard', [
            'title' => 'Dashboard',
            'capability' => 'manage_options',
        ]);

        $this->addMenuItem('contact', [
            'title' => 'Contact',
            'capability' => 'manage_options',
        ]);

        $this->addMenuItem('settings', [
            'title' => 'Settings',
            'capability' => 'manage_options',
        ]);
    }

    /**
     * Register admin menu pages
     *
     * @return void
     */
    public function registerMenuPages()
    {
        if (!current_user_can('manage_options')) {
            return;
        }

        global $submenu;

        // Add main menu page
        add_menu_page(
            'WP Boilerplate',
            'WP Boilerplate',
            'manage_options',
            'wp-boilerplate',
            [$this, 'renderAdminPage'],
            'dashicons-editor-code',
            25
        );

        // Make sure we have menu items
        if (empty($this->menuItems)) {
            // Add default menu items if none are defined
            $this->addMenuItem('dashboard', [
                'title' => 'Dashboard',
                'capability' => 'manage_options',
            ]);

            $this->addMenuItem('contact', [
                'title' => 'Contact',
                'capability' => 'manage_options',
            ]);

            $this->addMenuItem('settings', [
                'title' => 'Settings',
                'capability' => 'manage_options',
            ]);
        }

        // Add submenu items
        foreach ($this->menuItems as $slug => $item) {
            $submenu['wp-boilerplate'][$slug] = [
                $item['title'],
                isset($item['capability']) ? $item['capability'] : 'manage_options',
                'admin.php?page=wp-boilerplate#/' . ($slug === 'dashboard' ? '' : $slug),
            ];
        }
    }

    /**
     * Render admin page
     *
     * @return void
     */
    public function renderAdminPage()
    {

        echo '<div class="wp-boilerplate-admin-page" id="wp_boilerplate_app">
            <div class="main-menu">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="font-bold text-xl mr-4">WP Boilerplate</div>
                        <!-- Navigation will be rendered by Vue.js -->
                    </div>
                    <div class="text-sm text-gray-500">
                        v' . WP_BOILERPLATE_VERSION . '
                    </div>
                </div>
            </div>
            <router-view></router-view>
        </div>';
    }

    /**
     * Add a menu item
     *
     * @param string $slug Menu item slug
     * @param array $item Menu item data
     * @return self
     */
    public function addMenuItem($slug, $item)
    {
        $this->menuItems[$slug] = $item;

        return $this;
    }

    /**
     * Get all menu items
     *
     * @return array
     */
    public function getMenuItems()
    {
        return $this->menuItems;
    }
}
