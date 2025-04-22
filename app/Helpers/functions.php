<?php

/**
 * Global helper functions for the plugin
 */

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
        return WP_BOILERPLATE_URL . 'dist/' . $path;
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
        $file = WP_BOILERPLATE_DIR . 'app/Views/' . $view . '.php';

        if (!file_exists($file)) {
            return '';
        }

        extract($data);

        ob_start();
        include $file;
        $content = ob_get_clean();

        if ($return) {
            return $content;
        }

        echo $content;
    }
}
