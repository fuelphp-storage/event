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

class Queue
{
	/**
	 * @var  array  $queue  payloads per queue
	 */
	protected $queue = array();

	/**
	 * @var  array  $container  event container
	 */
	protected $container;

	/**
	 * Appends a payload to the queue
	 */
	public function queue($queue, $payload = array())
	{
		// Check for a queue stack
		if ( ! isset($this->queue[$queue]))
		{
			// Create one if it doesn't exist
			$this->queue = array();
		}

		if ( ! is_array($payload))
		{
			throw new \InvalidArgumentException('Queue payload must an array.');
		}

		// Append the payload to the queue
		$this->queue[$queue][] = $payload;

		return $this;
	}

	/**
	 * Retrieve the queue payload
	 *
	 * @param   string  $queue  queue name
	 * @return  array   queue payloads
	 */
	public function payload($queue)
	{
		return isset($this->queue[$queue]) ? $this->queue[$queue] : array();
	}

	/**
	 * Register a flusher.
	 *
	 * @param   string  $queue     queue name
	 * @param   mixed   $flusher   flusher
	 * @param   mixed   $context   flusher context
	 * @param   int     $priority  priority
	 */
	public function on($queue, $flusher, $context = null, $priority = 0)
	{
		// Ensure there is an event container.
		$this->container or $this->container = new Container();

		// Register the flusher to the event container
		$this->container->on($queue, $flusher, $context, $priority);

		return $this;
	}

	/**
	 * Remove a flusher.
	 *
	 * @param   string  $queue     queue name
	 * @param   mixed   $flusher   flusher
	 * @param   mixed   $context   flusher context
	 */
	public function off($queue = null, $flusher = null, $context = null)
	{
		// When there is no event container
		if ( ! $this->container)
		{
			// Skip execution
			return $this;
		}

		// Remove the flusher from the event container.
		$this->container->off($queue, $flusher, $context);

		return $this;
	}

	/**
	 * Flushes a queue. Adds a flusher when supplied.
	 *
	 * @param   string  $queue     queue name
	 * @param   mixed   $flusher   flusher
	 * @param   mixed   $context   flusher context
	 * @param   int     $priority  priority
	 */
	public function flush($queue, $flusher = null, $context = null, $priority = 0)
	{
		// Set the return array
		$return = array();

		if ($flusher)
		{
			$this->on($queue, $flusher, $context, $priority);
		}

		// When there is no event container
		if ( ! $this->container)
		{
			// Skip execution
			return $return;
		}

		// Get the queue payload
		$queuePayload = $this->payload($queue);

		foreach ($queuePayload as $payload)
		{
			// Prepend the event
			array_unshift($payload, $queue);

			$return[] = call_user_func_array(array($this->container, 'trigger'), $payload);
		}

		return $return;
	}

	/**
	 * Clear an event queue, removes all payloads and flushers.
	 *
	 * @param   string  $queue  queue name
	 * @return  object  $this
	 */
	public function clear($queue)
	{
		if (isset($this->queue[$queue]))
		{
			unset($this->queue[$queue]);
		}

		// When there is no event container
		if ( ! $this->container)
		{
			// Skip execution
			return $this;
		}

		// Remove all flushers
		$this->container->off($queue);

		return $this;
	}
}