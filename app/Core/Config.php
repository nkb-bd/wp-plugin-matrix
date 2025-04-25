<?php

namespace WpBoilerplate\Core;

/**
 * Config class
 * 
 * Handles plugin configuration
 */
class Config
{
    /**
     * Configuration values
     *
     * @var array
     */
    protected static $config = [];

    /**
     * Load configuration
     *
     * @param string $file Configuration file name without extension
     * @return void
     */
    public static function load($file)
    {
        $filePath = WP_BOILERPLATE_DIR . 'app/Config/' . $file . '.php';
        
        if (file_exists($filePath)) {
            $config = require $filePath;
            
            if (is_array($config)) {
                static::$config[$file] = $config;
            }
        }
    }

    /**
     * Get configuration value
     *
     * @param string $key Configuration key (format: file.key.subkey)
     * @param mixed $default Default value if key doesn't exist
     * @return mixed
     */
    public static function get($key, $default = null)
    {
        $parts = explode('.', $key);
        $file = array_shift($parts);
        
        // Load config file if not loaded yet
        if (!isset(static::$config[$file])) {
            static::load($file);
        }
        
        // If config file doesn't exist, return default
        if (!isset(static::$config[$file])) {
            return $default;
        }
        
        $value = static::$config[$file];
        
        // Traverse the config array
        foreach ($parts as $part) {
            if (!is_array($value) || !isset($value[$part])) {
                return $default;
            }
            
            $value = $value[$part];
        }
        
        return $value;
    }

    /**
     * Set configuration value
     *
     * @param string $key Configuration key (format: file.key.subkey)
     * @param mixed $value Configuration value
     * @return void
     */
    public static function set($key, $value)
    {
        $parts = explode('.', $key);
        $file = array_shift($parts);
        
        // Load config file if not loaded yet
        if (!isset(static::$config[$file])) {
            static::load($file);
        }
        
        // If config file doesn't exist, create it
        if (!isset(static::$config[$file])) {
            static::$config[$file] = [];
        }
        
        $config = &static::$config[$file];
        
        // Traverse the config array
        foreach ($parts as $i => $part) {
            // If we're at the last part, set the value
            if ($i === count($parts) - 1) {
                $config[$part] = $value;
                break;
            }
            
            // If the part doesn't exist or is not an array, create it
            if (!isset($config[$part]) || !is_array($config[$part])) {
                $config[$part] = [];
            }
            
            $config = &$config[$part];
        }
    }

    /**
     * Check if configuration value exists
     *
     * @param string $key Configuration key (format: file.key.subkey)
     * @return bool
     */
    public static function has($key)
    {
        $parts = explode('.', $key);
        $file = array_shift($parts);
        
        // Load config file if not loaded yet
        if (!isset(static::$config[$file])) {
            static::load($file);
        }
        
        // If config file doesn't exist, return false
        if (!isset(static::$config[$file])) {
            return false;
        }
        
        $value = static::$config[$file];
        
        // Traverse the config array
        foreach ($parts as $part) {
            if (!is_array($value) || !isset($value[$part])) {
                return false;
            }
            
            $value = $value[$part];
        }
        
        return true;
    }
}
