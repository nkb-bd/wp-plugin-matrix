<?php

namespace WPPluginMatrixBoilerPlate\Core;

/**
 * HookTracker class
 * 
 * Provides wrappers for WordPress hook functions to track hook registrations
 */
class HookTracker
{
    /**
     * Add an action hook with tracking
     *
     * @param string $hook Hook name
     * @param callable $callback Callback function
     * @param int $priority Priority (default: 10)
     * @param int $accepted_args Number of arguments (default: 1)
     * @return bool
     */
    public static function addAction($hook, $callback, $priority = 10, $accepted_args = 1)
    {
        // Track the hook registration
        if (class_exists('\\WPPluginMatrixBoilerPlate\\Core\\Debug')) {
            Debug::registerHook($hook, $callback, $priority);
        }
        
        // Call WordPress function
        return add_action($hook, $callback, $priority, $accepted_args);
    }
    
    /**
     * Add a filter hook with tracking
     *
     * @param string $hook Hook name
     * @param callable $callback Callback function
     * @param int $priority Priority (default: 10)
     * @param int $accepted_args Number of arguments (default: 1)
     * @return bool
     */
    public static function addFilter($hook, $callback, $priority = 10, $accepted_args = 1)
    {
        // Track the hook registration
        if (class_exists('\\WPPluginMatrixBoilerPlate\\Core\\Debug')) {
            Debug::registerHook($hook, $callback, $priority);
        }
        
        // Call WordPress function
        return add_filter($hook, $callback, $priority, $accepted_args);
    }
    
    /**
     * Remove an action hook
     *
     * @param string $hook Hook name
     * @param callable $callback Callback function
     * @param int $priority Priority (default: 10)
     * @return bool
     */
    public static function removeAction($hook, $callback, $priority = 10)
    {
        return remove_action($hook, $callback, $priority);
    }
    
    /**
     * Remove a filter hook
     *
     * @param string $hook Hook name
     * @param callable $callback Callback function
     * @param int $priority Priority (default: 10)
     * @return bool
     */
    public static function removeFilter($hook, $callback, $priority = 10)
    {
        return remove_filter($hook, $callback, $priority);
    }
}
