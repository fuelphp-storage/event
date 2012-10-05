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

use Fuel\Event\Container;

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