<?php

namespace WPPluginMatrixBoilerPlate\Http\Controllers;

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
            'title' => 'WP Plugin Matrix BoilerPlate',
            'show_title' => 'yes',
        ], $atts, 'wp-plugin-matrix-boiler-plate');
        
        // Start output buffering
        ob_start();
        
        // Include the view
        include WP_PLUGIN_MATRIX_BOILER_PLATE_DIR . 'app/Views/shortcode.php';
        
        // Return the buffered content
        return ob_get_clean();
    }
}
