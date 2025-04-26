<?php

namespace WPPluginMatrixBoilerPlate\Core;

/**
 * Enhanced Asset Manager
 *
 * Handles script and style enqueuing with support for:
 * - Page-specific loading
 * - Conditional loading
 * - Admin vs. frontend assets
 * - Proper dependency management
 */
class Asset
{
    /**
     * Registered scripts
     *
     * @var array
     */
    protected $scripts = [];

    /**
     * Registered styles
     *
     * @var array
     */
    protected $styles = [];

    /**
     * Plugin page slug
     *
     * @var string
     */
    protected $pluginSlug = 'wp-plugin-matrix-boiler-plate';

    /**
     * Constructor
     */
    public function __construct()
    {
        // Register hooks for enqueuing assets
        add_action('wp_enqueue_scripts', [$this, 'enqueueFrontendAssets']);
        add_action('admin_enqueue_scripts', [$this, 'enqueueAdminAssets']);
    }

    /**
     * Get asset URL
     *
     * @param string $path Asset path
     * @return string
     */
    public function url($path)
    {
        return WP_PLUGIN_MATRIX_BOILER_PLATE_URL . 'dist/' . ltrim($path, '/');
    }

    /**
     * Register a script
     *
     * @param string $handle Script handle
     * @param string $path Script path (relative to dist directory)
     * @param array $deps Dependencies
     * @param string|bool $ver Version
     * @param bool $inFooter Whether to enqueue in footer
     * @param string $context Where to load the script (admin, frontend, or both)
     * @param callable|null $condition Optional condition callback
     * @return $this
     */
    public function registerScript($handle, $path, $deps = [], $ver = false, $inFooter = true, $context = 'both', $condition = null)
    {
        $this->scripts[$handle] = [
            'handle' => $handle,
            'path' => $path,
            'deps' => $deps,
            'ver' => $ver ?: WP_PLUGIN_MATRIX_BOILER_PLATE_VERSION,
            'inFooter' => $inFooter,
            'context' => $context,
            'condition' => $condition,
            'localize' => [],
        ];

        return $this;
    }

    /**
     * Register a style
     *
     * @param string $handle Style handle
     * @param string $path Style path (relative to dist directory)
     * @param array $deps Dependencies
     * @param string|bool $ver Version
     * @param string $media Media
     * @param string $context Where to load the style (admin, frontend, or both)
     * @param callable|null $condition Optional condition callback
     * @return $this
     */
    public function registerStyle($handle, $path, $deps = [], $ver = false, $media = 'all', $context = 'both', $condition = null)
    {
        $this->styles[$handle] = [
            'handle' => $handle,
            'path' => $path,
            'deps' => $deps,
            'ver' => $ver ?: WP_PLUGIN_MATRIX_BOILER_PLATE_VERSION,
            'media' => $media,
            'context' => $context,
            'condition' => $condition,
        ];

        return $this;
    }

    /**
     * Localize a script
     *
     * @param string $handle Script handle
     * @param string $objectName JavaScript object name
     * @param array $data Data to localize
     * @return $this
     */
    public function localizeScript($handle, $objectName, $data)
    {
        if (isset($this->scripts[$handle])) {
            $this->scripts[$handle]['localize'][] = [
                'objectName' => $objectName,
                'data' => $data,
            ];
        }

        return $this;
    }

    /**
     * Set plugin slug
     *
     * @param string $slug Plugin slug
     * @return $this
     */
    public function setPluginSlug($slug)
    {
        $this->pluginSlug = $slug;

        return $this;
    }

    /**
     * Enqueue frontend assets
     *
     * @return void
     */
    public function enqueueFrontendAssets()
    {
        // Skip if in admin
        if (is_admin()) {
            return;
        }

        $this->enqueueAssets('frontend');
    }

    /**
     * Enqueue admin assets
     *
     * @param string $hook Current admin page hook
     * @return void
     */
    public function enqueueAdminAssets($hook)
    {
        // Check if we're on our plugin's page using the URL parameter
        if (!isset($_GET['page']) || $_GET['page'] !== $this->pluginSlug) {
            return;
        }

        $this->enqueueAssets('admin', $hook);
    }

    /**
     * Enqueue assets for a specific context
     *
     * @param string $context Context (admin or frontend)
     * @param string|null $hook Current admin page hook (for admin context only)
     * @return void
     */
    protected function enqueueAssets($context, $hook = null)
    {
        // Enqueue scripts
        foreach ($this->scripts as $script) {
            // Skip if not for this context
            if ($script['context'] !== 'both' && $script['context'] !== $context) {
                continue;
            }

            // Check condition if provided
            if (is_callable($script['condition']) && !call_user_func($script['condition'], $hook)) {
                continue;
            }

            // Enqueue the script
            wp_enqueue_script(
                $script['handle'],
                $this->url($script['path']),
                $script['deps'],
                $script['ver'],
                $script['inFooter']
            );

            // Localize the script if needed
            if (!empty($script['localize'])) {
                foreach ($script['localize'] as $localize) {
                    wp_localize_script(
                        $script['handle'],
                        $localize['objectName'],
                        $localize['data']
                    );
                }
            }
        }

        // Enqueue styles
        foreach ($this->styles as $style) {
            // Skip if not for this context
            if ($style['context'] !== 'both' && $style['context'] !== $context) {
                continue;
            }

            // Check condition if provided
            if (is_callable($style['condition']) && !call_user_func($style['condition'], $hook)) {
                continue;
            }

            // Enqueue the style
            wp_enqueue_style(
                $style['handle'],
                $this->url($style['path']),
                $style['deps'],
                $style['ver'],
                $style['media']
            );
        }
    }
}
