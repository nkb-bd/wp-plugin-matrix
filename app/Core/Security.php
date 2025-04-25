<?php

namespace WpBoilerplate\Core;

/**
 * Security class
 *
 * Provides security-related helpers
 */
class Security
{
    /**
     * Verify CSRF token
     *
     * @param string $action Action name
     * @param string $nonce Nonce value
     * @return bool
     */
    public static function verifyNonce($action, $nonce = null)
    {
        if ($nonce === null) {
            $nonce = isset($_REQUEST['_wpnonce']) ? $_REQUEST['_wpnonce'] : '';
        }

        // Use global function
        return function_exists('wp_verify_nonce') ? wp_verify_nonce($nonce, $action) : false;
    }

    /**
     * Generate CSRF token
     *
     * @param string $action Action name
     * @return string
     */
    public static function createNonce($action)
    {
        // Use global function
        return function_exists('wp_create_nonce') ? wp_create_nonce($action) : md5($action . time());
    }

    /**
     * Generate CSRF field
     *
     * @param string $action Action name
     * @return string
     */
    public static function nonceField($action)
    {
        // Use global function
        return function_exists('wp_nonce_field') ? wp_nonce_field($action, '_wpnonce', true, false) : '';
    }

    /**
     * Sanitize input
     *
     * @param mixed $input Input to sanitize
     * @param string $type Sanitization type
     * @return mixed
     */
    public static function sanitize($input, $type = 'text')
    {
        switch ($type) {
            case 'email':
                return function_exists('sanitize_email') ? sanitize_email($input) : filter_var($input, FILTER_SANITIZE_EMAIL);

            case 'url':
                return function_exists('sanitize_url') ? sanitize_url($input) : filter_var($input, FILTER_SANITIZE_URL);

            case 'title':
                return function_exists('sanitize_title') ? sanitize_title($input) : strtolower(preg_replace('/[^a-zA-Z0-9\-]/', '-', $input));

            case 'key':
                return function_exists('sanitize_key') ? sanitize_key($input) : strtolower(preg_replace('/[^a-z0-9_\-]/', '', $input));

            case 'html':
                return function_exists('wp_kses_post') ? wp_kses_post($input) : strip_tags($input, '<p><a><br><strong><em><ul><ol><li><h1><h2><h3><h4><h5><h6>');

            case 'textarea':
                return function_exists('sanitize_textarea_field') ? sanitize_textarea_field($input) : htmlspecialchars($input, ENT_QUOTES, 'UTF-8');

            case 'int':
            case 'integer':
                return intval($input);

            case 'float':
                return floatval($input);

            case 'array':
                if (!is_array($input)) {
                    return [];
                }

                $sanitized = [];
                foreach ($input as $key => $value) {
                    $sanitized[function_exists('sanitize_key') ? sanitize_key($key) : strtolower(preg_replace('/[^a-z0-9_\-]/', '', $key))] = self::sanitize($value);
                }

                return $sanitized;

            case 'text':
            default:
                return function_exists('sanitize_text_field') ? sanitize_text_field($input) : trim(htmlspecialchars($input, ENT_QUOTES, 'UTF-8'));
        }
    }

    /**
     * Escape output
     *
     * @param mixed $output Output to escape
     * @param string $type Escaping type
     * @return mixed
     */
    public static function escape($output, $type = 'html')
    {
        switch ($type) {
            case 'attr':
                return function_exists('esc_attr') ? esc_attr($output) : htmlspecialchars($output, ENT_QUOTES, 'UTF-8');

            case 'url':
                return function_exists('esc_url') ? esc_url($output) : filter_var($output, FILTER_SANITIZE_URL);

            case 'js':
                return function_exists('esc_js') ? esc_js($output) : addslashes($output);

            case 'textarea':
                return function_exists('esc_textarea') ? esc_textarea($output) : htmlspecialchars($output, ENT_QUOTES, 'UTF-8');

            case 'html':
            default:
                return function_exists('esc_html') ? esc_html($output) : htmlspecialchars($output, ENT_QUOTES, 'UTF-8');
        }
    }

    /**
     * Check if current user has capability
     *
     * @param string|array $capability Capability or array of capabilities
     * @param int|null $userId User ID
     * @return bool
     */
    public static function userCan($capability, $userId = null)
    {
        if (is_array($capability)) {
            foreach ($capability as $cap) {
                if (function_exists('current_user_can') && current_user_can($cap, $userId)) {
                    return true;
                }
            }

            return false;
        }

        return function_exists('current_user_can') ? current_user_can($capability, $userId) : false;
    }

    /**
     * Validate request method
     *
     * @param string|array $method Method or array of methods
     * @return bool
     */
    public static function validateMethod($method)
    {
        $currentMethod = $_SERVER['REQUEST_METHOD'];

        if (is_array($method)) {
            return in_array($currentMethod, array_map('strtoupper', $method));
        }

        return strtoupper($method) === $currentMethod;
    }
}
