<?php

namespace WpBoilerplate\Facades;

use WpBoilerplate\Core\Facade;

/**
 * Logger Facade
 * 
 * @method static void log(string $message, string $level = 'info', array $context = [])
 * @method static void debug(string $message, array $context = [])
 * @method static void info(string $message, array $context = [])
 * @method static void warning(string $message, array $context = [])
 * @method static void error(string $message, array $context = [])
 */
class Logger extends Facade
{
    /**
     * Get the registered name of the component
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'logger';
    }
}
