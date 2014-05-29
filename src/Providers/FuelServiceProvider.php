<?php
/**
 * @package    Fuel\Event
 * @version    2.0
 * @author     Fuel Development Team
 * @license    MIT License
 * @copyright  2010 - 2014 Fuel Development Team
 * @link       http://fuelphp.com
 */

namespace Fuel\Event\Providers;

use Fuel\Dependency\ServiceProvider;

/**
 * FuelPHP ServiceProvider class for this package
 *
 * @package  Fuel\Display
 *
 * @since  1.0.0
 */
class FuelServiceProvider extends ServiceProvider
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
			return $dic->resolve('Fuel\Event\Container');
		});

		// \Fuel\Event\Queue
		$this->register('queue', function ($dic)
		{
			return $dic->resolve('Fuel\Event\Queue');
		});
	}
}
