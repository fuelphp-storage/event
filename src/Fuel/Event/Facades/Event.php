<?php
/**
 * @package    Fuel\Event
 * @version    2.0
 * @author     Fuel Development Team
 * @license    MIT License
 * @copyright  2010 - 2013 Fuel Development Team
 * @link       http://fuelphp.com
 */

namespace Fuel\Event\Facades;

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
