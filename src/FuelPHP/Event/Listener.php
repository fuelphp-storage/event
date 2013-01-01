<?php

/**
 * Event Package
 *
 * @package    FuelPHP\Event
 * @version    1.0.0
 * @license    MIT License
 * @copyright  2010 - 2012 Fuel Development Team
 */

namespace FuelPHP\Event;

class Listener
{
	/**
	 * @var  string  $event  event name
	 */
	protected $event;

	/**
	 * @var  mixed  $handler  event handler
	 */
	protected $handler;

	/**
	 * @var  mixed  $context  handler context
	 */
	protected $context;

	/**
	 * @var  bool  $propagate  continue propagation boolean
	 */
	protected $propagate = true;

	/**
	 * @var  int  $priority  priority
	 */
	public $priority = 0;

	/**
	 * Constructor
	 *
	 * @param  string  $event     event name
	 * @param  mixed   $handler   handler
	 * @param  mixed   $context   closure context
	 * @param  int     $priority  closure context
	 */
	public function __construct($event, $handler, $context = null, $priority = null)
	{
		if (is_int($context))
		{
			$priority = $context;
			$context = null;
		}

		if ( ! is_callable($handler))
		{
			throw new \InvalidArgumentException('Handlers must be callable');
		}

		if ($context and ! is_object($context))
		{
			throw new \InvalidArgumentException('Contexts must be objects');
		}

		$this->event = $event;
		$this->handler = $handler;
		$this->context = $context;
		$this->priority = $priority;
	}

	/**
	 * Prevents further events from being fired.
	 *
	 * @return  object  $this
	 */
	public function stopPropagation()
	{
		$this->propagate = false;

		return $this;
	}

	/**
	 * Returns wether event propagation should continue.
	 *
	 * @return  bool  wether event propagation should continue.
	 */
	public function propagationStopped()
	{
		return ! $this->propagate;
	}

	/**
	 * Retrieve the Event name
	 *
	 * @return  string  event name
	 */
	public function name()
	{
		return $this->name;
	}

	/**
	 * Retrieve the Event handler
	 *
	 * @return  mixed  event handler
	 */
	public function handler()
	{
		return $this->handler;
	}

	/**
	 * Retrieve the Event context
	 *
	 * @return  mixed  event context
	 */
	public function context()
	{
		return $this->context;
	}

	/**
	 * Retrieve wether the event object matches a set of event params.
	 *
	 * @return  bool  wether the event object matches the params
	 */
	public function is($event, $handler, $context)
	{
		if (($event === null or $this->event === $event) and
			($handler === null or $this->handler === $handler) and
			($context === null or $this->context === $context))
		{
			return true;
		}

		return false;
	}

	/**
	 * Invoke handler forewarder.
	 *
	 * @param   string  $event  triggered event
	 * @param   array   $args   handler arguments
	 * @return  mixed   handler return value
	 */
	public function __invoke($event, $args)
	{
		$handler = $this->handler;

		// Store the original event
		$original_event = $this->event;

		// Set the triggered event
		// This is needed when 'all' events are fired
		$this->event = $event;

		if ($this->context)
		{
			if ( ! ($handler instanceof \Closure))
			{
				throw new Exception('Handler must be a Closure in order to bind a contaxt to.');
			}

			if ( ! ($handler = $handler->bindTo($this->context, $this->context)))
			{
				throw new Exception('Context could not be bound to handler.');
			}
		}

		// Prepend self to the arguments array
		array_unshift($args, $this);

		// Fetch the handler result
		$result = call_user_func_array($handler, $args);

		// Restore the old event name
		$this->event = $original_event;

		return $result;
	}
}