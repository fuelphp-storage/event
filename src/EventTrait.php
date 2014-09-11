<?php
/**
 * @package    Fuel\Event
 * @version    2.0
 * @author     Fuel Development Team
 * @license    MIT License
 * @copyright  2010 - 2014 Fuel Development Team
 * @link       http://fuelphp.com
 */

namespace Fuel\Event;

trait EventTrait
{
	/**
	 * @var  Container $_eventContainer  event container
	 */
	protected $_eventContainer;

	/**
	 * @var  boolean  $_eventBindSelf  wether to bind itself to the events
	 */
	protected $_eventBindSelf = false;

	/**
	 * @var  boolean  $_eventPrependSelf  wether to prepend itself to the arguments array
	 */
	protected $_eventPrependSelf = false;

	/**
	 * Attaches a new event.
	 *
	 * @param   string  $event    event name
	 * @param   mixed   $handler  event handler
	 * @param   mixed   $context  closure context
	 * @return  object  $this
	 */
	public function on($event, $handler, $context = null, $priority = 0)
	{
		// Check wether a priority is
		// given in place of a context.
		if (is_int($context))
		{
			// Switch then around
			$priority = $context;
			$context = null;
		}

		// When the object is self binding
		if ($context === null and $this->_eventBindSelf)
		{
			// Set the context to $this
			$context = $this;
		}

		// Ensure there is a Container
		$this->_eventContainer or $this->_eventContainer = new Container();

		// Add the event
		$this->_eventContainer->on($event, $handler, $context, $priority);

		// Remain chainable
		return $this;
	}

	/**
	 * Removes one or more events.
	 *
	 * @param   string  $event    event name
	 * @param   mixed   $handler  event handler
	 * @param   mixed   $context  closure context
	 * @return  object  $this
	 */
	public function off($event = null, $handler = null, $context = null)
	{
		// When a container is set
		if ($this->_eventContainer)
		{
			// When the object is self binding
			if ($context === null and $this->_eventBindSelf)
			{
				// Set the context to $this
				$context = $this;
			}

			// Add the event to the container
			$this->_eventContainer->on($event, $handler, $context);
		}

		// Remain chainable
		return $this;
	}

	/**
	 * Trigger an event.
	 *
	 * @param   string  $event  event to trigger
	 * @return  object  $this
	 */
	public function trigger($event, $arguments = array())
	{
		// Check for self prepend
		$this->_eventPrependSelf and array_unshift($arguments, $this);

		// Add the event type
		array_unshift($arguments, $event);

		// When a container is set
		if ($this->_eventContainer)
		{
			// Add the event to the container
			call_user_func_array(array($this->_eventContainer, 'trigger'), $arguments);
		}

		// Remain chainable
		return $this;
	}
}
