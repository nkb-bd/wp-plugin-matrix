<?php

namespace WpBoilerplate\Services;

/**
 * Class Activator
 * 
 * Handles plugin activation
 */
class Activator
{
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
        /*
        * Database creation commented out,
        * If you need any database just active this function below
        * and write your own query at createUserFavorite function
        */
        $this->sampleTable();
    }

    /**
     * Create sample table
     *
     * @return void
     */
    public function sampleTable()
    {
        global $wpdb;
        $charsetCollate = $wpdb->get_charset_collate();
        $tableName = $wpdb->prefix . 'wp_boilerplate_user_favorites';
        
        $sql = "CREATE TABLE $tableName (
            id int(10) NOT NULL AUTO_INCREMENT,
            user_id int(10) NOT NULL,
            post_id int(10) NOT NULL,
            created_at timestamp NULL DEFAULT NULL,
            updated_at timestamp NULL DEFAULT NULL,
            PRIMARY KEY (id)
        ) $charsetCollate;";

        $this->runSQL($sql, $tableName);
    }

    /**
     * Run SQL query
     *
     * @param string $sql SQL query
     * @param string $tableName Table name
     * @return void
     */
    private function runSQL($sql, $tableName)
    {
        global $wpdb;
        
        if ($wpdb->get_var("SHOW TABLES LIKE '$tableName'") != $tableName) {
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
        }
    }
}
