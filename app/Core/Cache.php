<?php

namespace WPPluginMatrixBoilerPlate\Core;

/**
 * Cache class
 * 
 * A wrapper for WordPress Transient API with additional features
 */
class Cache
{
    /**
     * Cache prefix
     * 
     * @var string
     */
    protected $prefix = 'wp_plugin_matrix_boiler_plate_';

    /**
     * Default expiration time in seconds
     * 
     * @var int
     */
    protected $defaultExpiration = 3600; // 1 hour

    /**
     * Get a cached value
     * 
     * @param string $key Cache key
     * @param mixed $default Default value if cache is not found
     * @param bool $usePrefix Whether to use the cache prefix
     * @return mixed
     */
    public function get($key, $default = null, $usePrefix = true)
    {
        $key = $usePrefix ? $this->prefix . $key : $key;
        $cached = get_transient($key);
        
        if ($cached === false) {
            return $default;
        }
        
        return $cached;
    }

    /**
     * Set a cached value
     * 
     * @param string $key Cache key
     * @param mixed $value Value to cache
     * @param int|null $expiration Expiration time in seconds
     * @param bool $usePrefix Whether to use the cache prefix
     * @return bool
     */
    public function set($key, $value, $expiration = null, $usePrefix = true)
    {
        $key = $usePrefix ? $this->prefix . $key : $key;
        $expiration = $expiration ?? $this->defaultExpiration;
        
        return set_transient($key, $value, $expiration);
    }

    /**
     * Check if a cache key exists
     * 
     * @param string $key Cache key
     * @param bool $usePrefix Whether to use the cache prefix
     * @return bool
     */
    public function has($key, $usePrefix = true)
    {
        $key = $usePrefix ? $this->prefix . $key : $key;
        return get_transient($key) !== false;
    }

    /**
     * Delete a cached value
     * 
     * @param string $key Cache key
     * @param bool $usePrefix Whether to use the cache prefix
     * @return bool
     */
    public function delete($key, $usePrefix = true)
    {
        $key = $usePrefix ? $this->prefix . $key : $key;
        return delete_transient($key);
    }

    /**
     * Remember a value in cache
     * 
     * If the item doesn't exist in the cache, store the result of the callback
     * 
     * @param string $key Cache key
     * @param int|null $expiration Expiration time in seconds
     * @param callable $callback Callback to generate the value
     * @param bool $usePrefix Whether to use the cache prefix
     * @return mixed
     */
    public function remember($key, $expiration, $callback, $usePrefix = true)
    {
        $value = $this->get($key, null, $usePrefix);
        
        if ($value !== null) {
            return $value;
        }
        
        $value = $callback();
        $this->set($key, $value, $expiration, $usePrefix);
        
        return $value;
    }

    /**
     * Flush all cached items with the plugin prefix
     * 
     * @return bool
     */
    public function flush()
    {
        global $wpdb;
        
        $options = $wpdb->options;
        $prefix = $this->prefix;
        
        // Get all transients with our prefix
        $transients = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT option_name FROM $options WHERE option_name LIKE %s OR option_name LIKE %s",
                "_transient_$prefix%",
                "_transient_timeout_$prefix%"
            )
        );
        
        // Delete all found transients
        $count = 0;
        foreach ($transients as $transient) {
            $key = str_replace(['_transient_', '_transient_timeout_'], '', $transient->option_name);
            if (delete_transient($key)) {
                $count++;
            }
        }
        
        return $count > 0;
    }

    /**
     * Set the default expiration time
     * 
     * @param int $seconds Expiration time in seconds
     * @return self
     */
    public function setDefaultExpiration($seconds)
    {
        $this->defaultExpiration = $seconds;
        return $this;
    }

    /**
     * Set the cache prefix
     * 
     * @param string $prefix Cache prefix
     * @return self
     */
    public function setPrefix($prefix)
    {
        $this->prefix = $prefix;
        return $this;
    }
}
