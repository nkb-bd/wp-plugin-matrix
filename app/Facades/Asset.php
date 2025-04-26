<?php

namespace WPPluginMatrixBoilerPlate\Facades;

use WPPluginMatrixBoilerPlate\Core\Facade;

/**
 * Asset Facade
 *
 * @method static string url(string $path)
 * @method static \WPPluginMatrixBoilerPlate\Core\Asset registerScript(string $handle, string $path, array $deps = [], string|bool $ver = false, bool $inFooter = true, string $context = 'both', callable|null $condition = null)
 * @method static \WPPluginMatrixBoilerPlate\Core\Asset registerStyle(string $handle, string $path, array $deps = [], string|bool $ver = false, string $media = 'all', string $context = 'both', callable|null $condition = null)
 * @method static \WPPluginMatrixBoilerPlate\Core\Asset localizeScript(string $handle, string $objectName, array $data)
 * @method static \WPPluginMatrixBoilerPlate\Core\Asset setPluginSlug(string $slug)
 * @method static \WPPluginMatrixBoilerPlate\Core\Asset enqueueAdminAssets(string $hook)
 * @method static \WPPluginMatrixBoilerPlate\Core\Asset enqueueFrontendAssets()
 */
class Asset extends Facade
{
	/**
	 * Get the facade accessor
	 *
	 * @return string
	 */
	protected static function getFacadeAccessor()
	{
		return 'asset';
	}
}
