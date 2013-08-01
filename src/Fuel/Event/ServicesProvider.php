<?php
/**
 * @package    Fuel\Event
 * @version    2.0
 * @author     Fuel Development Team
 * @license    MIT License
 * @copyright  2010 - 2013 Fuel Development Team
 * @link       http://fuelphp.com
 */

namespace Fuel\Event;

use Fuel\Dependency\ServiceProvider;

/**
 * ServicesProvider class
 *
 * Defines the services published by this namespace to the DiC
 *
 * @package  Fuel\Display
 *
 * @since  1.0.0
 */
class ServicesProvider extends ServiceProvider
{
	/**
	 * @var  array  list of service names provided by this provider
	 */
	public $provides = array('event', 'queue');

	/**
	 * Service provider definitions
	 */
	public function provide()
	{
		// \Fuel\Event\Container
		$this->register('event', function ($dic)
		{
			return new Container();
		});

		// \Fuel\Event\Queue
		$this->register('queue', function ($dic)
		{
			return new Queue();
		});
	}
}
