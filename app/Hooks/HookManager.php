<?php

namespace WPPluginMatrixBoilerPlate\Hooks;

use WPPluginMatrixBoilerPlate\Facades\Logger;
use WPPluginMatrixBoilerPlate\Hooks\Handlers\AdminHandler;
use WPPluginMatrixBoilerPlate\Hooks\Handlers\AjaxHandler;
use WPPluginMatrixBoilerPlate\Hooks\Handlers\ApiHandler;
use WPPluginMatrixBoilerPlate\Hooks\Handlers\AssetHandler;
use WPPluginMatrixBoilerPlate\Hooks\Handlers\ShortcodeHandler;

/**
 * HookManager class
 *
 * Manages all WordPress hooks for the plugin
 */
class HookManager
{
    /**
     * Register all hooks
     *
     * This method is called during the 'plugins_loaded' action hook in WordPress,
     * which runs after WordPress has finished loading but before any headers are sent.
     * It's an ideal time to register hooks, filters, and other WordPress integrations.
     *
     * @return void
     */
    public function registerHooks()
    {
        // Register all hook handlers
        $handlers = [
            'admin' => new AdminHandler(),
            'ajax' => new AjaxHandler(),
            'api' => new ApiHandler(),
            'asset' => new AssetHandler(),
            'shortcode' => new ShortcodeHandler()
        ];

        foreach ($handlers as $name => $handler) {
            // Track when each handler is registered
            if (class_exists('\WPPluginMatrixBoilerPlate\Core\Debug')) {
                \WPPluginMatrixBoilerPlate\Core\Debug::addToSequence("HookManager: Registering {$name} handler", microtime(true));
            }

            $handler->register();

            if (class_exists('\WPPluginMatrixBoilerPlate\Core\Debug')) {
                \WPPluginMatrixBoilerPlate\Core\Debug::addToSequence("HookManager: {$name} handler registered", microtime(true));
            }
        }

        // Log that hooks have been registered
        Logger::debug('All hooks registered successfully');
    }
}
