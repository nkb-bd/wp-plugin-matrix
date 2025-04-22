<?php

namespace WpBoilerplate\Http\Controllers;

/**
 * Class ShortcodeController
 * 
 * Handles shortcode rendering
 */
class ShortcodeController
{
    /**
     * Render shortcode
     *
     * @param array $atts Shortcode attributes
     * @param string|null $content Shortcode content
     * @return string
     */
    public function render($atts, $content = null)
    {
        // Parse attributes
        $atts = shortcode_atts([
            'title' => 'WP Boilerplate',
            'show_title' => 'yes',
        ], $atts, 'wp-boilerplate');
        
        // Start output buffering
        ob_start();
        
        // Include the view
        include WP_BOILERPLATE_DIR . 'app/Views/shortcode.php';
        
        // Return the buffered content
        return ob_get_clean();
    }
}
