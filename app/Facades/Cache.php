<?php

namespace WPPluginMatrixBoilerPlate\Facades;

use WPPluginMatrixBoilerPlate\Core\Facade;

/**
 * Cache Facade
 *
 * @method static mixed get(string $key, mixed $default = null, bool $usePrefix = true)
 * @method static bool set(string $key, mixed $value, int|null $expiration = null, bool $usePrefix = true)
 * @method static bool has(string $key, bool $usePrefix = true)
 * @method static bool delete(string $key, bool $usePrefix = true)
 * @method static mixed remember(string $key, int|null $expiration, callable $callback, bool $usePrefix = true)
 * @method static bool flush()
 * @method static self setDefaultExpiration(int $seconds)
 * @method static self setPrefix(string $prefix)
 */
class Cache extends Facade
{
	/**
	 * Get the facade accessor
	 *
	 * @return string
	 */
	protected static function getFacadeAccessor()
	{
		return 'cache';
	}
}
