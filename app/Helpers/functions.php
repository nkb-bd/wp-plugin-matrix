<?php

/**
 * Global helper functions for the plugin
 *
 * These functions provide a procedural interface to the facade classes.
 * You can use either the functions or the facades, depending on your preference.
 */

use WpBoilerplate\Facades\Asset;
use WpBoilerplate\Facades\Cache;
use WpBoilerplate\Facades\Config;
use WpBoilerplate\Facades\Logger;
use WpBoilerplate\Facades\Security;
use WpBoilerplate\Facades\View;

if (!function_exists('wp_boilerplate_get_avatar')) {
    /**
     * Get Gravatar URL by Email
     *
     * @param string $email Email address
     * @param int $size Size of the avatar
     * @return string Gravatar URL
     */
    function wp_boilerplate_get_avatar($email, $size)
    {
        $hash = md5(strtolower(trim($email)));

        return apply_filters('wp_boilerplate_get_avatar',
            "https://www.gravatar.com/avatar/{$hash}?s={$size}&d=mm&r=g",
            $email
        );
    }
}

/**
 * Get plugin asset URL
 *
 * @param string $path Asset path
 * @return string Full URL to the asset
 */
if (!function_exists('wp_boilerplate_asset')) {
    function wp_boilerplate_asset($path)
    {
        return Asset::url($path);
    }
}

/**
 * Get plugin view
 *
 * @param string $view View name
 * @param array $data Data to pass to the view
 * @param bool $return Whether to return the view or echo it
 * @return string|void
 */
if (!function_exists('wp_boilerplate_view')) {
    function wp_boilerplate_view($view, $data = [], $return = false)
    {
        return View::render($view, $data, $return);
    }
}

/**
 * Get cache instance
 *
 * @return \WpBoilerplate\Core\Cache
 */
if (!function_exists('wp_boilerplate_cache')) {
    function wp_boilerplate_cache()
    {
        return new \WpBoilerplate\Core\Cache();
    }
}

/**
 * Get a cached value
 *
 * @param string $key Cache key
 * @param mixed $default Default value if cache is not found
 * @return mixed
 */
if (!function_exists('wp_boilerplate_cache_get')) {
    function wp_boilerplate_cache_get($key, $default = null)
    {
        return Cache::get($key, $default);
    }
}

/**
 * Set a cached value
 *
 * @param string $key Cache key
 * @param mixed $value Value to cache
 * @param int|null $expiration Expiration time in seconds
 * @return bool
 */
if (!function_exists('wp_boilerplate_cache_set')) {
    function wp_boilerplate_cache_set($key, $value, $expiration = null)
    {
        return Cache::set($key, $value, $expiration);
    }
}

/**
 * Remember a value in cache
 *
 * @param string $key Cache key
 * @param int|null $expiration Expiration time in seconds
 * @param callable $callback Callback to generate the value
 * @return mixed
 */
if (!function_exists('wp_boilerplate_cache_remember')) {
    function wp_boilerplate_cache_remember($key, $expiration, $callback)
    {
        return Cache::remember($key, $expiration, $callback);
    }
}

/**
 * Delete a cached value
 *
 * @param string $key Cache key
 * @return bool
 */
if (!function_exists('wp_boilerplate_cache_delete')) {
    function wp_boilerplate_cache_delete($key)
    {
        return Cache::delete($key);
    }
}

/**
 * Sanitize input
 *
 * @param mixed $input Input to sanitize
 * @param string $type Sanitization type
 * @return mixed
 */
if (!function_exists('wp_boilerplate_sanitize')) {
    function wp_boilerplate_sanitize($input, $type = 'text')
    {
        return Security::sanitize($input, $type);
    }
}

/**
 * Escape output
 *
 * @param mixed $output Output to escape
 * @param string $type Escaping type
 * @return mixed
 */
if (!function_exists('wp_boilerplate_escape')) {
    function wp_boilerplate_escape($output, $type = 'html')
    {
        return Security::escape($output, $type);
    }
}

/**
 * Verify CSRF token
 *
 * @param string $action Action name
 * @param string $nonce Nonce value
 * @return bool
 */
if (!function_exists('wp_boilerplate_verify_nonce')) {
    function wp_boilerplate_verify_nonce($action, $nonce = null)
    {
        return Security::verifyNonce($action, $nonce);
    }
}

/**
 * Generate CSRF token
 *
 * @param string $action Action name
 * @return string
 */
if (!function_exists('wp_boilerplate_create_nonce')) {
    function wp_boilerplate_create_nonce($action)
    {
        return Security::createNonce($action);
    }
}

/**
 * Generate CSRF field
 *
 * @param string $action Action name
 * @return string
 */
if (!function_exists('wp_boilerplate_nonce_field')) {
    function wp_boilerplate_nonce_field($action)
    {
        return Security::nonceField($action);
    }
}

/**
 * Get configuration value
 *
 * @param string $key Configuration key (format: file.key.subkey)
 * @param mixed $default Default value if key doesn't exist
 * @return mixed
 */
if (!function_exists('wp_boilerplate_config')) {
    function wp_boilerplate_config($key, $default = null)
    {
        return Config::get($key, $default);
    }
}

/**
 * Format a date
 *
 * @param string|int $date Date to format
 * @param string $format Date format
 * @return string
 */
if (!function_exists('wp_boilerplate_format_date')) {
    function wp_boilerplate_format_date($date, $format = 'F j, Y')
    {
        if (is_numeric($date)) {
            $date = date('Y-m-d H:i:s', $date);
        }

        return date_i18n($format, strtotime($date));
    }
}

/**
 * Get plugin URL
 *
 * @param string $path Path to append to the plugin URL
 * @return string
 */
if (!function_exists('wp_boilerplate_url')) {
    function wp_boilerplate_url($path = '')
    {
        return WP_BOILERPLATE_URL . ltrim($path, '/');
    }
}

/**
 * Get plugin directory path
 *
 * @param string $path Path to append to the plugin directory
 * @return string
 */
if (!function_exists('wp_boilerplate_path')) {
    function wp_boilerplate_path($path = '')
    {
        return WP_BOILERPLATE_DIR . ltrim($path, '/');
    }
}

/**
 * Log a message
 *
 * @param string $message Message to log
 * @param string $level Log level (debug, info, warning, error)
 * @param array $context Additional context
 * @return void
 */
if (!function_exists('wp_boilerplate_log')) {
    function wp_boilerplate_log($message, $level = 'info', $context = [])
    {
        Logger::log($message, $level, $context);
    }
}

/**
 * Log a debug message
 *
 * @param string $message Message to log
 * @param array $context Additional context
 * @return void
 */
if (!function_exists('wp_boilerplate_debug')) {
    function wp_boilerplate_debug($message, $context = [])
    {
        Logger::debug($message, $context);
    }
}

/**
 * Log an info message
 *
 * @param string $message Message to log
 * @param array $context Additional context
 * @return void
 */
if (!function_exists('wp_boilerplate_info')) {
    function wp_boilerplate_info($message, $context = [])
    {
        Logger::info($message, $context);
    }
}

/**
 * Log a warning message
 *
 * @param string $message Message to log
 * @param array $context Additional context
 * @return void
 */
if (!function_exists('wp_boilerplate_warning')) {
    function wp_boilerplate_warning($message, $context = [])
    {
        Logger::warning($message, $context);
    }
}

/**
 * Log an error message
 *
 * @param string $message Message to log
 * @param array $context Additional context
 * @return void
 */
if (!function_exists('wp_boilerplate_error')) {
    function wp_boilerplate_error($message, $context = [])
    {
        Logger::error($message, $context);
    }
}

/**
 * Add an action with tracking
 *
 * @param string $hook Hook name
 * @param callable $callback Callback function
 * @param int $priority Priority (default: 10)
 * @param int $accepted_args Number of arguments (default: 1)
 * @return bool
 */
if (!function_exists('wp_boilerplate_add_action')) {
    function wp_boilerplate_add_action($hook, $callback, $priority = 10, $accepted_args = 1)
    {
        return \WpBoilerplate\Core\HookTracker::addAction($hook, $callback, $priority, $accepted_args);
    }
}

/**
 * Add a filter with tracking
 *
 * @param string $hook Hook name
 * @param callable $callback Callback function
 * @param int $priority Priority (default: 10)
 * @param int $accepted_args Number of arguments (default: 1)
 * @return bool
 */
if (!function_exists('wp_boilerplate_add_filter')) {
    function wp_boilerplate_add_filter($hook, $callback, $priority = 10, $accepted_args = 1)
    {
        return \WpBoilerplate\Core\HookTracker::addFilter($hook, $callback, $priority, $accepted_args);
    }
}

/**
 * Get the App instance
 *
 * @return \WpBoilerplate\Core\App
 */
if (!function_exists('wp_boilerplate_app')) {
    function wp_boilerplate_app()
    {
        return \WpBoilerplate\Core\App::getInstance();
    }
}
