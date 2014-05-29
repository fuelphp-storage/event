<?php
/**
 * @package    Fuel\Event
 * @version    2.0
 * @author     Fuel Development Team
 * @license    MIT License
 * @copyright  2010 - 2014 Fuel Development Team
 * @link       http://fuelphp.com
 */

namespace Fuel\Event\Facades;

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
