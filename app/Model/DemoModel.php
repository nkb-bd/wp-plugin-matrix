<?php

namespace WPPluginMatrixBoilerPlate\Model;

/**
 * Class Snippet
 *
 * Sample model class
 */
class DemoModel
{
    /**
     * Table name
     *
     * @var string
     */
    protected $table;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        global $wpdb;
        $this->table = $wpdb->prefix . 'wp_plugin_matrix_boiler_plate_user_favorites';
    }
    
    /**
     * Get all snippets
     *
     * @return array
     */
    public function all()
    {
        global $wpdb;
        return $wpdb->get_results("SELECT * FROM {$this->table}");
    }
    
    /**
     * Get snippet by ID
     *
     * @param int $id Snippet ID
     * @return object|null
     */
    public function find($id)
    {
        global $wpdb;
        return $wpdb->get_row($wpdb->prepare("SELECT * FROM {$this->table} WHERE id = %d", $id));
    }
    
    /**
     * Create new snippet
     *
     * @param array $data Snippet data
     * @return int|false
     */
    public function create($data)
    {
        global $wpdb;
        
        $data['created_at'] = current_time('mysql');
        $data['updated_at'] = current_time('mysql');
        
        $inserted = $wpdb->insert($this->table, $data);
        
        if ($inserted) {
            return $wpdb->insert_id;
        }
        
        return false;
    }
    
    /**
     * Update snippet
     *
     * @param int $id Snippet ID
     * @param array $data Snippet data
     * @return bool
     */
    public function update($id, $data)
    {
        global $wpdb;
        
        $data['updated_at'] = current_time('mysql');
        
        return $wpdb->update(
            $this->table,
            $data,
            ['id' => $id]
        );
    }
    
    /**
     * Delete snippet
     *
     * @param int $id Snippet ID
     * @return bool
     */
    public function delete($id)
    {
        global $wpdb;
        
        return $wpdb->delete(
            $this->table,
            ['id' => $id]
        );
    }
}
