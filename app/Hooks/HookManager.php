<?php

namespace WpBoilerplate\Hooks;

use WpBoilerplate\Hooks\Handlers\AdminHandler;
use WpBoilerplate\Hooks\Handlers\AjaxHandler;
use WpBoilerplate\Hooks\Handlers\ApiHandler;
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
     * @return void
     */
    public function registerHooks()
    {
        // Register admin hooks
        $adminHandler = new AdminHandler();
        $adminHandler->register();
        
        // Register AJAX hooks
        $ajaxHandler = new AjaxHandler();
        $ajaxHandler->register();
        
        // Register API hooks
        $apiHandler = new ApiHandler();
        $apiHandler->register();
        
        // Register shortcode hooks
        $shortcodeHandler = new ShortcodeHandler();
        $shortcodeHandler->register();
    }
}
