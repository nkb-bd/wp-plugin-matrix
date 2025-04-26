<?php

namespace WPPluginMatrixBoilerPlate\Facades;

use WPPluginMatrixBoilerPlate\Core\Facade;

/**
 * Security Facade
 *
 * @method static bool verifyNonce(string $action, string $nonce = null)
 * @method static string createNonce(string $action)
 * @method static string nonceField(string $action)
 * @method static mixed sanitize(mixed $input, string $type = 'text')
 * @method static mixed escape(mixed $output, string $type = 'html')
 * @method static bool userCan(string|array $capability, int|null $userId = null)
 * @method static bool validateMethod(string|array $method)
 */
class Security extends Facade
{
	/**
	 * Get the facade accessor
	 *
	 * @return string
	 */
	protected static function getFacadeAccessor()
	{
		return 'security';
	}
}
