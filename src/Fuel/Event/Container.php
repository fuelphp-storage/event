<?php

/**
 * Event Package
 *
 * @package    Fuel\Event
 * @version    1.0.0
 * @license    MIT License
 * @copyright  2010 - 2012 Fuel Development Team
 */

namespace Fuel\Event;

class Container
{
	/**
	 * @var  array  $events  registered events
	 */
	protected $listners = array();

	/**
	 * Container constructor
	 *
	 * @param  array  $events  events array
	 */
	public function __construct(array $events = array())
	{
		$this->listeners = $events;
	}

	/**
	 * Attaches a new event.
	 *
	 * @param   string  $event     event name
	 * @param   mixed   $handler   event handler
	 * @param   mixed   $context   closure context
	 * @param   int     $priority  event priority
	 * @return  object  $this
	 */
	public function on($event, $handler, $context = null, $priority = 0)
	{
		if ( ! is_callable($handler))
		{
			throw new \InvalidArgumentException('The $handler should be callable.');
		}

		if ( ! isset($this->listeners[$event]))
		{
			$this->listeners[$event] = array();
		}

		$this->listeners[$event][] = new Listener($event, $handler, $context, $priority);

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
		// When there are no events to fire
		if (($event and ! isset($this->listeners[$event])) or empty($this->listeners[$event]))
		{
			// Skip execution
			return $this;
		}

		// When an event name is given, only fetch that stack.
		$events = $event ? $this->listeners[$event] : $this->listeners;

		foreach ($event as $k => $e)
		{
			// If the event matches, delete it
			if ($e->is($event, $handler, $context))
			{
				// Use the event param.
				if ($event)
				{
					// Saves a function call ;-)
					unset($this->listeners[$event][$k]);
				}
				else
				{
					// Otherwise, retrieve the event name from the Event object.
					unset($this->listeners[$e->event()][$k]);
				}
			}
		}

		return $this;
	}

	/**
	 * Trigger an event.
	 *
	 * @param   string  $event  event to trigger
	 * @return  array   return values
	 */
	public function trigger($event)
	{
		// Get the handlers
		$listeners = $this->getListeners($event);
		
		// Set return array
		$return = array();

		// When there are no handlers
		if (empty($listeners))
		{
			// Skip execution
			return $return;
		}

		// Get the event arguments.
		$args = func_get_args();

		// Shift the event name off the arguments array
		array_shift($args);

		// Sort the events.
		usort($listeners, function($a, $b)
		{
			if ($a->priority >= $b->priority)
			{
				return 1;
			}

			return -1;
		});

		foreach ($listeners as $listeners)
		{
			// Prepend the listener object.
			array_unshift($args, $listeners);

			// Fire the event and fetch the result
			$return[] = $listeners($args);

			// When the bubbling is prevented.
			if($listeners->propagationStopped())
			{
				// Break the event loop.
				break;
			}

			// Remove the event object.
			array_shift($args);
		}

		return $return;
	}

	/**
	 * Retrieve the handlers for a given type, including the all events.
	 *
	 * @param   string  $event  event name
	 * @return  array   array of event objects for a given type
	 */
	public function getListeners($event)
	{
		// Get the special all events
		$all_listeners = isset($this->listeners['all']) ? $this->listeners['all'] : array();

		// Get the handlers
		$event_listeners = isset($this->listeners[$event]) ? $this->listeners[$event] : array();

		// Return the merged handlers array
		return array_merge(array(), $all_listeners, $event_listeners);
	}
}