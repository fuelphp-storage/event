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
	protected $events = array();

	/**
	 * Container constructor
	 *
	 * @param  array  $events  events array
	 */
	public function __construct(array $events = array())
	{
		$this->events = $events;
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

		if ( ! isset($this->events[$event]))
		{
			$this->events[$event] = array();
		}

		if (is_int($context))
		{
			$priority = $context;
			$context = null;
		}

		$this->events[$event][] = new Event($event, $handler, $context, $priority);

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
		if (($event and ! isset($this->events[$event])) or empty($this->events[$event]))
		{
			// Skip execution
			return $this;
		}

		// When an event name is given, only fetch that stack.
		$events = $event ? $this->events[$event] : $this->events;

		foreach ($event as $k => $e)
		{
			// If the event matches, delete it
			if ($e->is($event, $handler, $context))
			{
				// Use the event param.
				if ($event)
				{
					// Saves a function call ;-)
					unset($this->events[$event][$k]);
				}
				else
				{
					// Otherwise, retrieve the event name from the Event object.
					unset($this->events[$e->event()][$k]);
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
		// When there are no handlers
		if ( ! isset($this->events[$event]))
		{
			// Skip execution
			return $this;
		}

		// Get the event arguments.
		$args = func_get_args();

		// Shift the event name off the arguments array
		array_shift($args);
		
		// Get the special all events
		$all_events = isset($this->events['all']) ? $this->events['all'] : array();

		// Get the events.
		$events = array_merge(array(), $all_events, $this->events[$event]);

		// Sort the events.
		usort($events, function($a, $b)
		{
			if ($a->priority >= $b->priority)
			{
				return 1;
			}

			return -1;
		});

		// Set return array
		$return = array();

		foreach ($events as $e)
		{
			// Prepend the event object.
			array_unshift($args, $e);

			// Fire the event and fetch the result
			$return[] = $e($args);

			// When the bubbling is prevented.
			if($e->bubblePrevented())
			{
				// Break the event loop.
				break;
			}

			// Remove the event object.
			array_shift($args);
		}

		return $return;
	}
}