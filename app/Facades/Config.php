<?php

namespace WpBoilerplate\Facades;

use WpBoilerplate\Core\Facade;

/**
 * Config Facade
 *
 * @method static mixed get(string $key, mixed $default = null)
 * @method static bool has(string $key)
 * @method static array all()
 * @method static void set(string $key, mixed $value)
 */
class Config extends Facade
{
    /**
     * Get the facade accessor
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'config';
    }
}
