<?php

/**
 * Event Package
 *
 * @package    Fuel\Event
 * @version    1.0.0
 * @license    MIT License
 * @copyright  2010 - 2012 Fuel Development Team
 */

namespace Fuel\Event;

abstract class Facade
{
	protected static $instances = array();

	public static function instance($name = '__default__', $events = array())
	{
		if (isset(static::$instances[$name]))
		{
			return static::$instances[$name];
		}

		static::$instances[$name] = new Container($events);

		return static::$instances[$name];
	}

	/**
	 * Forge a new event container
	 *
	 * @param   array   $events  events array
	 * @return  object  new Container instance
	 */
	public static function forge($events = array())
	{
		return new Container($events);
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