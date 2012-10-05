<?php

/**
 * Event Package
 *
 * @package    Fuel\Event
 * @version    1.0.0
 * @license    MIT License
 * @copyright  2010 - 2012 Fuel Development Team
 */

namespace Fuel\Event\Facade;

abstract class Base
{
	protected static $instances = array();

	/**
	 * Create and retrieve an instance.
	 *
	 * @param   string  $name    instance reference
	 * @param   array   $events  events array
	 */
	public static function instance($name = '__default__')
	{
		if (isset(static::$instances[$name]))
		{
			return static::$instances[$name];
		}

		static::$instances[$name] = static::forge();

		return static::$instances[$name];
	}

	/**
	 * Delete an instance from the facade.
	 *
	 * @param  mixed  $name  instance name or true for all
	 */
	public static function delete($name)
	{
		if ($name === true)
		{
			static::$instances = array();
		}
		elseif (isset(static::$instances[$name]))
		{
			unset(static::$instances[$name]);
		}
	}

	/**
	 * Get a new instance. Must be implemented by child classes.
	 *
	 * @return  object  new  instance
	 */
	public static function forge()
	{
		throw new Exception(get_called_class().' must define a ::forge function.');
	}

	/**
	 * Static call forwarder.
	 *
	 * @param   string  $func  called function
	 * @param   array   $args  function arguments
	 * @return  mixed   function result
	 */
	public static function __callStatic($func, $args)
	{
		$instance = static::instance();

		if ( ! method_exists($instance, $func))
		{
			throw new \BadMethodCallException('Call to undefined method '.$func);
		}

		return call_user_func_array(array($instance, $func), $args);
	}
}