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

class Event
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
	 * @var  bool  $bubble  continue bubbling boolean
	 */
	protected $bubble = true;

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
	public function preventBubbling()
	{
		$this->bubble = false;

		return $this;
	}

	/**
	 * Returns wether event bubbling should continue.
	 *
	 * @return  bool  wether event bubbling should continue.
	 */
	public function bubblePrevented()
	{
		return ! $this->bubble;
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
	 * Check wether the event object matches an event type.
	 *
	 * @return bool  wether the object matches the event type
	 */
	public function shouldFireOn($event)
	{
		return $this->event === $event;
	}

	/**
	 * Invoke handler forewarder.
	 *
	 * @param   array  $args  handler arguments
	 * @return  mixed  handler return value
	 */
	public function __invoke($args)
	{
		$handler = $this->handler;

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

		return call_user_func_array($handler, $args);
	}
}