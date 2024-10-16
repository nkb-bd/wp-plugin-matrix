<?php
namespace PluginClassName\Classes;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Ajax Handler Class
 * @since 1.0.0
 */
class AdminAjaxHandler
{
    public function registerEndpoints()
    {   
        $ajaxPrefix = 'woo_com_exporter_admin_ajax';
        add_action('wp_ajax_'.$ajaxPrefix, array($this, 'handeEndPoint'));
    }

    public function handeEndPoint()
    {
        $route = sanitize_text_field($_REQUEST['route']);

        $validRoutes = array(
            'test'                  => 'getTest',
        );

        if (isset($validRoutes[$route])) {
            do_action('doing_ajax_for_' . $route);
            return $this->{$validRoutes[$route]}();
        }
        do_action('wppayform/admin_ajax_handler_catch', $route);
    }

    protected function getTest(){
        return wp_send_json([
            'data' => "Response from Server: AdminAjaxHandler"
        ]);
    }
}