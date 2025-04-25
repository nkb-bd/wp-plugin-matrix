<?php

namespace WpBoilerplate\Hooks;

use WpBoilerplate\Facades\Logger;
use WpBoilerplate\Hooks\Handlers\AdminHandler;
use WpBoilerplate\Hooks\Handlers\AjaxHandler;
use WpBoilerplate\Hooks\Handlers\ApiHandler;
use WpBoilerplate\Hooks\Handlers\AssetHandler;
use WpBoilerplate\Hooks\Handlers\ShortcodeHandler;

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
            if (class_exists('\WpBoilerplate\Core\Debug')) {
                \WpBoilerplate\Core\Debug::addToSequence("HookManager: Registering {$name} handler", microtime(true));
            }

            $handler->register();

            if (class_exists('\WpBoilerplate\Core\Debug')) {
                \WpBoilerplate\Core\Debug::addToSequence("HookManager: {$name} handler registered", microtime(true));
            }
        }

        // Log that hooks have been registered
        Logger::debug('All hooks registered successfully');
    }
}
