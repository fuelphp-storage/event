<?php

/**
 * Event Package
 *
 * @package    FuelPHP\Event
 * @version    1.0.0
 * @license    MIT License
 * @copyright  2010 - 2012 Fuel Development Team
 */

namespace FuelPHP\Event\Facade;

use FuelPHP\Event\Container;

abstract class Event extends Base
{
	/**
	 * Get a new Container instance.
	 *
	 * @return  object  new Container instance
	 */
	public static function forge()
	{
		return new Container();
	}
}