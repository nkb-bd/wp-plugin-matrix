<?php

namespace WpBoilerplate\Core;

/**
 * Logger class
 *
 * Handles logging for the plugin
 */
class Logger
{
    /**
     * Log levels
     */
    const DEBUG = 'debug';
    const INFO = 'info';
    const WARNING = 'warning';
    const ERROR = 'error';

    /**
     * Log a message
     *
     * @param string $message Message to log
     * @param string $level Log level
     * @param array $context Additional context
     * @return void
     */
    public function log($message, $level = self::INFO, $context = [])
    {
        // Only log in development mode
        if (!defined('WP_DEBUG') || !WP_DEBUG) {
            return;
        }

        if (!$this->shouldLog($level)) {
            return;
        }

        $timestamp = current_time('mysql');
        $formatted = $this->formatMessage($message, $level, $timestamp, $context);

        error_log($formatted);
    }

    /**
     * Log a debug message
     *
     * @param string $message Message to log
     * @param array $context Additional context
     * @return void
     */
    public function debug($message, $context = [])
    {
        $this->log($message, self::DEBUG, $context);
    }

    /**
     * Log an info message
     *
     * @param string $message Message to log
     * @param array $context Additional context
     * @return void
     */
    public function info($message, $context = [])
    {
        $this->log($message, self::INFO, $context);
    }

    /**
     * Log a warning message
     *
     * @param string $message Message to log
     * @param array $context Additional context
     * @return void
     */
    public function warning($message, $context = [])
    {
        $this->log($message, self::WARNING, $context);
    }

    /**
     * Log an error message
     *
     * @param string $message Message to log
     * @param array $context Additional context
     * @return void
     */
    public function error($message, $context = [])
    {
        $this->log($message, self::ERROR, $context);
    }

    /**
     * Format a log message
     *
     * @param string $message Message to log
     * @param string $level Log level
     * @param string $timestamp Timestamp
     * @param array $context Additional context
     * @return string
     */
    protected function formatMessage($message, $level, $timestamp, $context = [])
    {
        $formatted = "[{$timestamp}] [{$level}] {$message}";

        if (!empty($context)) {
            $formatted .= ' ' . json_encode($context);
        }

        return $formatted;
    }

    /**
     * Check if we should log this message
     *
     * @param string $level Log level
     * @return bool
     */
    protected function shouldLog($level)
    {
        // We're already checking for WP_DEBUG in the log method,
        // so here we just do basic level filtering
        return true;
    }


}
