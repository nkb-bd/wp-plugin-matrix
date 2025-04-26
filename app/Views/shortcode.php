<?php
/**
 * Shortcode view
 * 
 * @var array $atts Shortcode attributes
 * @var string|null $content Shortcode content
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}
?>
<div class="wp-plugin-matrix-boiler-plate-shortcode">
    <?php if ($atts['show_title'] === 'yes'): ?>
        <h3 class="wp-plugin-matrix-boiler-plate-shortcode-title"><?php echo esc_html($atts['title']); ?></h3>
    <?php endif; ?>
    
    <div class="wp-plugin-matrix-boiler-plate-shortcode-content">
        <?php if ($content): ?>
            <?php echo wp_kses_post($content); ?>
        <?php else: ?>
            <p><?php _e('This is a sample shortcode from WP Plugin Matrix BoilerPlate.', 'wp-plugin-matrix-boiler-plate'); ?></p>
        <?php endif; ?>
    </div>
</div>
<style>
    .wp-plugin-matrix-boiler-plate-shortcode {
        background-color: #f9f9f9;
        border: 1px solid #ddd;
        padding: 20px;
        margin: 20px 0;
        border-radius: 4px;
    }
    
    .wp-plugin-matrix-boiler-plate-shortcode-title {
        margin-top: 0;
        margin-bottom: 15px;
        font-size: 18px;
        color: #2271b1;
    }
    
    .wp-plugin-matrix-boiler-plate-shortcode-content {
        font-size: 14px;
        line-height: 1.5;
    }
</style>
