<?php

namespace PluginClassName\Classes;

class LoadAssets
{
    public function admin()
    {   
        wp_enqueue_script(
            'pluginlowercase-script-boot',
            PLUGIN_CONST_URL.'assets/js/main.js',
            array('jquery'),
            PLUGIN_CONST_VERSION,
             true
        );
        wp_enqueue_style(
            'pluginlowercase-script-style-extra',
            PLUGIN_CONST_URL.'assets/js/main.css',
            [],
            PLUGIN_CONST_VERSION
        );

        wp_enqueue_style(
            'pluginlowercase-script-style',
            PLUGIN_CONST_URL.'assets/css/style.css',
            [],
            PLUGIN_CONST_VERSION
        );

    }
  
}
