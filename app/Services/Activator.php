<?php

namespace WpBoilerplate\Services;

use WpBoilerplate\Database\MigrationManager;

/**
 * Class Activator
 *
 * Handles plugin activation
 */
class Activator
{
    /**
     * Migration manager instance
     *
     * @var MigrationManager
     */
    protected $migrationManager;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->migrationManager = new MigrationManager();
    }

    /**
     * Run migrations
     *
     * @param bool $networkWide Whether to run for all sites in a multisite
     * @return void
     */
    public function migrateDatabases($networkWide = false)
    {
        global $wpdb;

        if ($networkWide) {
            // Retrieve all site IDs from this network
            if (function_exists('get_sites') && function_exists('get_current_network_id')) {
                $siteIds = get_sites(['fields' => 'ids', 'network_id' => get_current_network_id()]);
            } else {
                $siteIds = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs WHERE site_id = $wpdb->siteid;");
            }

            // Install the plugin for all these sites
            foreach ($siteIds as $siteId) {
                switch_to_blog($siteId);
                $this->migrate();
                restore_current_blog();
            }
        } else {
            $this->migrate();
        }
    }

    /**
     * Run migrations
     *
     * @return void
     */
    private function migrate()
    {
        // Run all pending migrations
        $this->migrationManager->runMigrations();
    }

    /**
     * Rollback migrations
     *
     * @param int $steps Number of batches to rollback
     * @return array
     */
    public function rollback($steps = 1)
    {
        return $this->migrationManager->rollback($steps);
    }

    /**
     * Reset all migrations
     *
     * @return array
     */
    public function reset()
    {
        return $this->migrationManager->reset();
    }
}
