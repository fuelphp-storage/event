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

/**
 * FuelPHP Composer library framework bootstrap
 */

// register the services of this composer library
\Dependency::getInstance()->registerService(new ServicesProvider);

// alias facade classes to global
\Alias::alias('Fuel\Event\Facades\Event', 'Event');
\Alias::alias('Fuel\Event\Facades\Queue', 'Queue');

/**
 * FuelPHP Composer library application bootstrap
 */
return function($app) {

	// your app initialisation code here
};
