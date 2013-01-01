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

use FuelPHP\Event\Queue as QueueContainer;

abstract class Queue extends Base
{
	/**
	 * Get a new Queue instance.
	 *
	 * @return  object  new Queue instance
	 */
	public static function forge()
	{
		return new QueueContainer();
	}
}