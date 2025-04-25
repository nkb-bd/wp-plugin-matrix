<?php

namespace WpBoilerplate\Core;

/**
 * Base Facade class
 *
 * Provides a static interface to non-static methods in other classes
 */
abstract class Facade
{
	/**
	 * The resolved object instances
	 *
	 * @var array
	 */
	protected static $resolvedInstances = [];

	/**
	 * The registered services
	 *
	 * @var array
	 */
	protected static $services = [];

	/**
	 * Get the facade accessor
	 *
	 * @return string
	 */
	abstract protected static function getFacadeAccessor();

	/**
	 * Register a service
	 *
	 * @param string $name Service name
	 * @param mixed $concrete Concrete implementation
	 * @return void
	 */
	public static function registerService($name, $concrete)
	{
		static::$services[$name] = $concrete;

		// Log for debugging
		if (class_exists('\WpBoilerplate\Core\Debug')) {
			\WpBoilerplate\Core\Debug::registerFacade($name, is_object($concrete) ? get_class($concrete) : gettype($concrete));
		}
	}

	/**
	 * Get all registered services
	 *
	 * @return array
	 */
	public static function getServices()
	{
		return static::$services;
	}

	/**
	 * Get the resolved instance
	 *
	 * @return mixed
	 */
	protected static function resolveFacadeInstance()
	{
		$accessor = static::getFacadeAccessor();

		if (isset(static::$resolvedInstances[$accessor])) {
			return static::$resolvedInstances[$accessor];
		}

		if (isset(static::$services[$accessor])) {
			$concrete = static::$services[$accessor];

			// If the concrete is a closure, execute it
			if ($concrete instanceof \Closure) {
				$concrete = $concrete();
			}

			return static::$resolvedInstances[$accessor] = $concrete;
		}

		// If no service is registered, try to create an instance from the accessor
		return static::$resolvedInstances[$accessor] = static::createFacadeInstance($accessor);
	}

	/**
	 * Create a new facade instance
	 *
	 * @param string $accessor
	 * @return mixed
	 */
	protected static function createFacadeInstance($accessor)
	{
		if (is_object($accessor)) {
			return $accessor;
		}

		if (class_exists($accessor)) {
			return new $accessor();
		}

		throw new \RuntimeException("Service [{$accessor}] not registered and is not a valid class.");
	}

	/**
	 * Clear all resolved instances
	 *
	 * @return void
	 */
	public static function clearResolvedInstances()
	{
		static::$resolvedInstances = [];
	}

	/**
	 * Clear all registered services
	 *
	 * @return void
	 */
	public static function clearServices()
	{
		static::$services = [];
	}

	/**
	 * Handle dynamic static method calls
	 *
	 * @param string $method
	 * @param array $args
	 * @return mixed
	 */
	public static function __callStatic($method, $args)
	{
		$instance = static::resolveFacadeInstance();

		if (!$instance) {
			throw new \RuntimeException('A facade root has not been set.');
		}

		return $instance->$method(...$args);
	}
}
