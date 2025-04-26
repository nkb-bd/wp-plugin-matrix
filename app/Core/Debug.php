<?php

namespace WPPluginMatrixBoilerPlate\Core;

/**
 * Debug class
 *
 * Provides centralized debugging capabilities for the plugin
 */
class Debug
{
    /**
     * Debug information storage
     *
     * @var array
     */
    protected static $debugInfo = [
        'facades' => [],
        'hooks' => [],
        'services' => [],
        'execution_sequence' => [],
    ];

    /**
     * Start time for performance tracking
     *
     * @var float
     */
    protected static $startTime = 0;

    /**
     * Initialize debugging
     *
     * @return void
     */
    public static function init()
    {
        // Only run in debug mode
        if (!defined('WP_DEBUG') || !WP_DEBUG) {
            return;
        }

        // Record start time
        self::$startTime = microtime(true);

        // Record initialization in sequence
        self::addToSequence('Debug::init', microtime(true));
    }

    /**
     * Register a facade for debugging
     *
     * @param string $name Facade name
     * @param string $class Facade class
     * @return void
     */
    public static function registerFacade($name, $class)
    {
        if (!defined('WP_DEBUG') || !WP_DEBUG) {
            return;
        }

        self::$debugInfo['facades'][$name] = $class;
        self::addToSequence("Facade registered: {$name}", microtime(true));
    }

    /**
     * Register a service for debugging
     *
     * @param string $name Service name
     * @param object $instance Service instance
     * @return void
     */
    public static function registerService($name, $instance)
    {
        if (!defined('WP_DEBUG') || !WP_DEBUG) {
            return;
        }

        self::$debugInfo['services'][$name] = get_class($instance);
        self::addToSequence("Service registered: {$name}", microtime(true));
    }

    /**
     * Register a hook for debugging
     *
     * @param string $hook Hook name
     * @param string $callback Callback function or method
     * @param int $priority Hook priority
     * @return void
     */
    public static function registerHook($hook, $callback, $priority = 10)
    {
        if (!defined('WP_DEBUG') || !WP_DEBUG) {
            return;
        }

        if (!isset(self::$debugInfo['hooks'][$hook])) {
            self::$debugInfo['hooks'][$hook] = [];
        }

        $callbackName = is_array($callback)
            ? (is_object($callback[0]) ? get_class($callback[0]) : $callback[0]) . '::' . $callback[1]
            : $callback;

        self::$debugInfo['hooks'][$hook][] = [
            'callback' => $callbackName,
            'priority' => $priority
        ];

        self::addToSequence("Hook registered: {$hook} -> {$callbackName} (priority: {$priority})", microtime(true));
    }

    /**
     * Add an item to the execution sequence
     *
     * @param string $action Action description
     * @param float $time Timestamp
     * @return void
     */
    public static function addToSequence($action, $time)
    {
        if (!defined('WP_DEBUG') || !WP_DEBUG) {
            return;
        }

        $elapsed = $time - self::$startTime;
        self::$debugInfo['execution_sequence'][] = [
            'time' => $time,
            'elapsed' => round($elapsed * 1000, 2) . 'ms',
            'action' => $action
        ];
    }

    /**
     * Get all debug information
     *
     * @return array
     */
    public static function getDebugInfo()
    {
        return self::$debugInfo;
    }

    /**
     * Dump debug information
     *
     * @param bool $return Whether to return the output instead of echoing
     * @return string|void
     */
    public static function dump($return = false)
    {
        if (!defined('WP_DEBUG') || !WP_DEBUG) {
            return $return ? '' : null;
        }

        $output = '';

        // Execution sequence
        $output .= '<h3>Execution Sequence</h3>';
        $output .= '<table style="width:100%;border-collapse:collapse;">';
        $output .= '<tr><th style="text-align:left;border-bottom:1px solid #ddd;padding:8px;">Time</th><th style="text-align:left;border-bottom:1px solid #ddd;padding:8px;">Elapsed</th><th style="text-align:left;border-bottom:1px solid #ddd;padding:8px;">Action</th></tr>';

        foreach (self::$debugInfo['execution_sequence'] as $item) {
            $output .= '<tr>';
            $dt = new \DateTime();
            $dt->setTimestamp((int)$item['time']);
            $microseconds = sprintf('.%06d', ($item['time'] - floor($item['time'])) * 1000000);
            $output .= '<td style="border-bottom:1px solid #eee;padding:8px;">' . $dt->format('H:i:s') . $microseconds . '</td>';
            $output .= '<td style="border-bottom:1px solid #eee;padding:8px;">' . $item['elapsed'] . '</td>';
            $output .= '<td style="border-bottom:1px solid #eee;padding:8px;">' . htmlspecialchars($item['action']) . '</td>';
            $output .= '</tr>';
        }

        $output .= '</table>';

        // Services
        $output .= '<h3>Registered Services</h3>';
        $output .= '<table style="width:100%;border-collapse:collapse;">';
        $output .= '<tr><th style="text-align:left;border-bottom:1px solid #ddd;padding:8px;">Name</th><th style="text-align:left;border-bottom:1px solid #ddd;padding:8px;">Class</th></tr>';

        foreach (self::$debugInfo['services'] as $name => $class) {
            $output .= '<tr>';
            $output .= '<td style="border-bottom:1px solid #eee;padding:8px;">' . htmlspecialchars($name) . '</td>';
            $output .= '<td style="border-bottom:1px solid #eee;padding:8px;">' . htmlspecialchars($class) . '</td>';
            $output .= '</tr>';
        }

        $output .= '</table>';

        // Facades
        $output .= '<h3>Registered Facades</h3>';
        $output .= '<table style="width:100%;border-collapse:collapse;">';
        $output .= '<tr><th style="text-align:left;border-bottom:1px solid #ddd;padding:8px;">Name</th><th style="text-align:left;border-bottom:1px solid #ddd;padding:8px;">Class</th></tr>';

        foreach (self::$debugInfo['facades'] as $name => $class) {
            $output .= '<tr>';
            $output .= '<td style="border-bottom:1px solid #eee;padding:8px;">' . htmlspecialchars($name) . '</td>';
            $output .= '<td style="border-bottom:1px solid #eee;padding:8px;">' . htmlspecialchars($class) . '</td>';
            $output .= '</tr>';
        }

        $output .= '</table>';

        // Hooks
        $output .= '<h3>Registered Hooks</h3>';
        $output .= '<table style="width:100%;border-collapse:collapse;">';
        $output .= '<tr><th style="text-align:left;border-bottom:1px solid #ddd;padding:8px;">Hook</th><th style="text-align:left;border-bottom:1px solid #ddd;padding:8px;">Callback</th><th style="text-align:left;border-bottom:1px solid #ddd;padding:8px;">Priority</th></tr>';

        foreach (self::$debugInfo['hooks'] as $hook => $callbacks) {
            foreach ($callbacks as $callback) {
                $output .= '<tr>';
                $output .= '<td style="border-bottom:1px solid #eee;padding:8px;">' . htmlspecialchars($hook) . '</td>';
                $output .= '<td style="border-bottom:1px solid #eee;padding:8px;">' . htmlspecialchars($callback['callback']) . '</td>';
                $output .= '<td style="border-bottom:1px solid #eee;padding:8px;">' . $callback['priority'] . '</td>';
                $output .= '</tr>';
            }
        }

        $output .= '</table>';
        if ($return) {
            return $output;
        }

        echo $output;
    }
}
