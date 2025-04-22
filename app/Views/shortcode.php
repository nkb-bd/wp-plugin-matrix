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
<div class="wp-boilerplate-shortcode">
    <?php if ($atts['show_title'] === 'yes'): ?>
        <h3 class="wp-boilerplate-shortcode-title"><?php echo esc_html($atts['title']); ?></h3>
    <?php endif; ?>
    
    <div class="wp-boilerplate-shortcode-content">
        <?php if ($content): ?>
            <?php echo wp_kses_post($content); ?>
        <?php else: ?>
            <p><?php _e('This is a sample shortcode from WP Boilerplate.', 'wp-boilerplate'); ?></p>
        <?php endif; ?>
    </div>
</div>
<style>
    .wp-boilerplate-shortcode {
        background-color: #f9f9f9;
        border: 1px solid #ddd;
        padding: 20px;
        margin: 20px 0;
        border-radius: 4px;
    }
    
    .wp-boilerplate-shortcode-title {
        margin-top: 0;
        margin-bottom: 15px;
        font-size: 18px;
        color: #2271b1;
    }
    
    .wp-boilerplate-shortcode-content {
        font-size: 14px;
        line-height: 1.5;
    }
</style>
