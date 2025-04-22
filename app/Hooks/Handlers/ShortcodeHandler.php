<?php

namespace WpBoilerplate\Hooks\Handlers;

use WpBoilerplate\Http\Controllers\ShortcodeController;

/**
 * ShortcodeHandler class
 * 
 * Handles shortcode-related hooks
 */
class ShortcodeHandler
{
    /**
     * Register hooks
     *
     * @return void
     */
    public function register()
    {
        // Register shortcodes
        add_shortcode('wp-boilerplate', [$this, 'renderShortcode']);
    }
    
    /**
     * Render shortcode
     *
     * @param array $atts Shortcode attributes
     * @param string|null $content Shortcode content
     * @return string
     */
    public function renderShortcode($atts, $content = null)
    {
        $controller = new ShortcodeController();
        return $controller->render($atts, $content);
    }
}
