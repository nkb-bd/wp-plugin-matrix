<?php

namespace WpBoilerplate\Facades;

use WpBoilerplate\Core\Facade;

/**
 * View Facade
 *
 * @method static string|void render(string $view, array $data = [], bool $return = false)
 */
class View extends Facade
{
	/**
	 * Get the facade accessor
	 *
	 * @return string
	 */
	protected static function getFacadeAccessor()
	{
		return 'view';
	}
}
