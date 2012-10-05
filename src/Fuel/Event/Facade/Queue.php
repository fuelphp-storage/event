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

use Fuel\Event\Queue as QueueContainer;

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